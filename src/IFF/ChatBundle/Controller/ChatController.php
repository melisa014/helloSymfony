<?php

namespace IFF\ChatBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Friend;
use DateTime;
use IFF\ChatBundle\Entity\Message;
use IFF\ChatBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Debug\Debug;

/**
 * @Route("/chat")
 */
class ChatController extends Controller
{
    /**
     * @Route("/")
     * 
     * @return Response
     */
    public function indexAction(): Response
    {
        $errors = [
            'anyFriends' => '',
            'sendMessage' => '',
            'showMessages' => '',
        ];
        $em = $this->getDoctrine()->getManager();
        
        // Достаём из БД всех друзей активного пользователя
        $activeUser = $this->getUser();
        $friends = $em->getRepository(User::class)->findBy(['id' => 14]);
        
        // Это закоментированно, пока не создано поле friends для юзеров
//        $friends = [];
//        $friendList = $activeUser->getFriends()->toArray();
//        foreach ($friendList as $friend) {
//            $friends[] = $em->getRepository(User::class)->findBy($friendId);
//        }
//        if (empty($friends)) {
//            $errors['anyFriends'] = 'У Вас пока нет друзей';
//        }
        
        // Достаём из БД все сообщения переписки активного пользователя с выбранным другом
        $em->getRepository(Message::class)
                ->findBy([
                    'userFrom' => $activeUser->getId(),
//                    'userTo' => 
                 ]);
        
        
        $form = $this->createForm(ChatType::class);
        
        return $this->render('IFFChatBundle:Chat:index.html.twig', [
            'form' => $form->createView(),
            'error' => $errors,
            'friends' => $friends,
        ]);
    }
    
    /**
     * @Route("/saving")
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function saveMessagesAction(Request $request): JsonResponse
    {
        $toUser = $this->getDoctrine()
                ->getManager()
                ->getRepository(User::class)
                ->find($request->get('toUser'));
        $content = $request->get('message');
        $fromUser = $this->getUser();
                
        $message = new Message();
        $message->setContent($content);
        $message->setTimestamp(new DateTime());
        
        $message->setUserFrom($fromUser);
        $message->setUserTo($toUser);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();
        
        return new JsonResponse([
            'Юзер' => $toUser,
            'Сообщение' => $message
        ]);
    }
    
    /**
     * Выполняет загрузку сообщений из базы данных и отправку их пользователю через ajax виде java-скрипта
     * 
     * @Route("/loading")
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function loadMessagesAction(Request $request): JsonResponse
    {
        // тут мы получили переменную переданную нашим java-скриптом при помощи ajax
        // это:  $_POST['last'] - номер последнего сообщения которое загрузилось у пользователя

        $last_message_id = intval($_POST['last']); // возвращает целое значение переменной

        // выполняем запрос к базе данных для получения 10 сообщений последних сообщений с номером большим чем $last_message_id
        $query = mysql_query("SELECT * FROM messages WHERE ( id > $last_message_id ) ORDER BY id DESC LIMIT 10");

        // проверяем есть ли какие-нибудь новые сообщения
        if (mysql_num_rows($query) > 0) {
        // начинаем формировать javascript который мы передадим клиенту
        $js = 'var chat = $("#chat_area");'; // получаем "указатель" на div, в который мы добавим новые сообщения

        // следующий конструкцией мы получаем массив сообщений из нашего запроса
        $messages = array();
        while ($row = mysql_fetch_array($query)) {
        $messages[] = $row;
        }

        // записываем номер последнего сообщения
        // [0] - это вернёт нам первый элемент в массиве $messages, но так как мы выполнили запрос с параметром "DESC" (в обратном порядке),
        // то это получается номер последнего сообщения в базе данных
        $last_message_id = $messages[0]['id'];

        // переворачиваем массив (теперь он в правильном порядке)
        $messages = array_reverse($messages);

        // идём по всем элементам массива $messages
        foreach ($messages as $value) {
        // продолжаем формировать скрипт для отправки пользователю
        $js .= 'chat.append("<span>' . $value['name'] . '&raquo; ' . $value['text'] . '</span>");'; // добавить сообщние (<span>Имя &raquo; текст сообщения</span>) в наш div
        }

        $js .= "last_message_id = $last_message_id;"; // запишем номер последнего полученного сообщения, что бы в следующий раз начать загрузку с этого сообщения

        // отправляем полученный код пользователю, где он будет выполнен при помощи функции eval()
        echo $js;
        }

        
        
        $errors['showMessages'] = '';
        
        $messages = [];
        
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findBy([
            'user_id' => $data['user_id'],
            'lastMessageId' => $lastMessageId
            
        ]);
        
        
        
        if (empty($messages)) {
            $errors['showMessages'] = 'Здесь ещё нет ни одного сообщения';
        }
        
        return JsonResponse([
            'messages' => $messages,
            'error' => $errors,
        ]);
    }
    
    /**
     * Создаёт 2 записи в таблице Friend: для 1 и 2 юзера
     * 
     * @Route("/add")
     */
    public function addFriendAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $activeUser = $this->getUser();
        $friend = $em->getRepository(User::class)->find(1);
        
        $activeUser->addMyFriend($friend); // записывает в БД и в поле ActiveUser-a
//        $activeUser->addFriendsWithMe($friend); // не записывает в БД, записывает в поле ActiveUser-a, нужно использовать только чтобы доставать тех, кто со мной дружит
//        
        $friend->addMyFriend($activeUser); // записывает в БД и в поле Friend-a
//        $friend->addFriendsWithMe($activeUser); // не записывает в БД, записывает в поле Friend-a
        
        $em->persist($activeUser);
        $em->persist($friend);
        $em->flush();
        
        $friends_1 = $activeUser->getMyFriends();
        $friendsWhitMe_1 = $activeUser->getFriendsWithMe();       
        
        $friends = $friend->getMyFriends();
        $friendsWhitMe = $friend->getFriendsWithMe();

        dump($friends_1->getValues());
        dump($friendsWhitMe_1->getValues());
        
        dump($friends->getValues());
        dump($friendsWhitMe->getValues());
        die('sfe');

        return $this->redirectToRoute('homepage');
    }
    
    /**
     * Удаляет 2 записи в таблице Friend: для 1 и 2 юзера
     * 
     * @Route("/remove")
     */
    public function removeFriendAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $friend = $em->getRepository(User::class)->find(2);
        $activeUser = $this->getUser();
        
        $activeUser->removeMyFriend($friend);
        $activeUser->removeFriendsWithMe($friend);
        
        $friend->removeMyFriend($activeUser);
        $friend->removeFriendsWithMe($activeUser);
        
        $em->persist($activeUser);
        $em->persist($friend);
        $em->flush(); 
        
        return $this->redirectToRoute('homepage');
    }
    
    /**
     * @Route("/test")
     * 
     * @param int $friendId
     */
    public function testAction(int $friendId)
    {
        return new $this->redirectToRoute('homepage');
    }
    
}
