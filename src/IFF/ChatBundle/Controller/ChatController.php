<?php

namespace IFF\ChatBundle\Controller;

use AppBundle\Entity\User;
use DateTime;
use IFF\ChatBundle\Entity\Message;
use IFF\ChatBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

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
            'showMessages' => '',
            'sendMessage' => '',
        ];
        
        // Достаём из БД всех друзей активного пользователя
        $activeUser = $this->getUser();
        $friendList = $activeUser->getMyFriends(); 
        if (empty($friendList)) {
            $errors['anyFriends'] = 'У Вас пока нет друзей';
        }
        
        //Достаём из БД все последние сообщения переписок со всеми друзьми активного пользователя
        $userLastMessages = $this->findUsersLastMessages($activeUser, $friendList->toArray());
        
        $form = $this->createForm(ChatType::class);
        
        return $this->render('IFFChatBundle:Chat:index.html.twig', [
            'form' => $form->createView(),
            'error' => $errors,
            'friends' => $friendList,
            'lastMessages' => $userLastMessages,
        ]);
    }
    
    /**
     * Возвращает массив последних сообщений активного пользователя из переписок
     *  со всеми его друзьми. Индексы массива - id друзей
     * 
     * @param User $activeUser
     * @param array $friendList
     * 
     * @return array $usersLastMessages
     */
    public function findUsersLastMessages(User $activeUser, array $friendList): array
    {
        $messageRepo = $this->getDoctrine()->getManager()->getRepository(Message::class);
        $usersLastMessages = [];
        
        // для всех друзей активного пользователя достанем всю переписку
        foreach ($friendList as $friend) {
            
            $userToUserMessages = $this->findAllUserToUserMessages($activeUser, $friend);
            
            // определим последнее сообщение от друга активному пользователю и наоборот
            $usersLastMessageId = 0;
            
            foreach ($userToUserMessages as $message) {
                if ($message->getId() > $usersLastMessageId) {
                    $usersLastMessageId = $message->getId();
                }
            }
            
            // сохраняем в массив последнее сообщение с индексом == id друга
            if (!empty($userToUserMessages)){
                $usersLastMessages[$friend->getId()] = $messageRepo->find($usersLastMessageId)->getContent();
            }
            else {
                $usersLastMessages[$friend->getId()] = "Сообщений нет";
            }
        }
        
        return $usersLastMessages;
    }
    
    /**
     * Возвращает массив всех сообщений переписки активного пользователя с данным другом 
     * отсортированный по возрастанию id сообщений
     * 
     * @param User $activeUser
     * @param User $friend
     * 
     * @return array $userToUserMessages
     */
    public function findAllUserToUserMessages(User $activeUser, User $friend): array
    {
        $messageRepo = $this->getDoctrine()->getManager()->getRepository(Message::class);
        
        // достанем всю переписку пользователей
        $activeToFriendMessages = $messageRepo->findBy([
            'userFrom' => $activeUser,
            'userTo' => $friend,
        ]);
        $friendToActiveMessages = $messageRepo->findBy([
            'userFrom' => $friend,
            'userTo' => $activeUser,
        ]);

        // записываем все сообщения в 1 массив
        $userToUserMessages = [];
        foreach ($activeToFriendMessages as $message){
            $userToUserMessages[] = $message;
        }
        foreach ($friendToActiveMessages as $message){
            $userToUserMessages[] = $message;
        }
        
        // сортируем объекты сообщений по возрастанию id сообщений
        uasort($userToUserMessages, function($a, $b){
            if ($a->getId() == $b->getId()) {
                return 0; 
            }
            return ($a->getId() < $b->getId()) ? -1 : 1;
        });
        
        return $userToUserMessages;
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
        $errors['showMessages'] = '';
        $userRepo = $this->getDoctrine()
                ->getManager()
                ->getRepository(User::class);
        
        // достаём переданные ajax-ом переменные
        $activeUser = $userRepo->find($request->get('activeUser'));
        $friend = $userRepo->find($request->get('friend'));
        $lastMessageId = $request->get('lastMessageId');
        
        
        $allMessages = $this->findAllUserToUserMessages($activeUser, $friend);
        
        if (empty($allMessages)) {
            $errors['showMessages'] = 'Здесь ещё нет ни одного сообщения'; 
            
            return new JsonResponse([
                'loadingMessages' => null,
                'lastLoadMessageId' => null,
                'error' => $errors,
            ]);
        }
            
        // если id последнего сообщения не передаётся, достаём всю переписку пользователей
        // эта часть работает
        if (empty($lastMessageId)) {
            $loadingMessages = $allMessages;
            $lastLoadMessageId = end($allMessages)->getId();
            
            return new JsonResponse([
                'loadingMessages' => $loadingMessages,
                'lastLoadMessageId' => $lastLoadMessageId,
                'error' => $errors,
            ]);
        }
        
        // если получен id последнего загруженного сообщения, достаём только сообщения 
        //  где id > id последнего загруженного
        foreach ($allMessages as $message) {
            if ($message->getId() > $lastMessageId) {
                $loadingMessages[] = $message;
            }
        }
        $lastLoadMessageId = end($loadingMessages)->getId();
        
        return new JsonResponse([
            'loadingMessages' => $loadingMessages,
            'lastLoadMessageId' => $lastLoadMessageId,
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
        $friend = $em->getRepository(User::class)->find(2);
        
        $activeUser->addMyFriend($friend); // записывает в БД и в поле ActiveUser-a
        
        $friend->addMyFriend($activeUser); // записывает в БД и в поле Friend-a
        
        $em->persist($activeUser);
        $em->persist($friend);
        $em->flush();
        
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
        
        $friend = $em->getRepository(User::class)->find(3);
        $activeUser = $this->getUser();
        
        $activeUser->removeMyFriend($friend);
        
        $friend->removeMyFriend($activeUser);
        
        $em->persist($activeUser);
        $em->persist($friend);
        $em->flush(); 
        
        return $this->redirectToRoute('homepage');
    }
    
    /**
     * @Route("/check")
     */
    public function testAction()
    {
         $em = $this->getDoctrine()->getManager();
        
        $activeUser = $this->getUser();
        $friend = $em->getRepository(User::class)->find(3);
        
        //Печатает, кто с кем дружит(для 2х пользователей). по таблице в БД, 
        // к сожалению, не всегда понятно, пусты ли поля myFriends и friendsWithMe
//        $friends_1 = $activeUser->getMyFriends();
//        $friendsWhitMe_1 = $activeUser->getFriendsWithMe();       
//        
//        $friends = $friend->getMyFriends();
//        $friendsWhitMe = $friend->getFriendsWithMe();
//
//        dump($friends_1->getValues());
//        dump($friendsWhitMe_1->getValues());
//        
//        dump($friends->getValues());
//        dump($friendsWhitMe->getValues());
        
        return new $this->redirectToRoute('homepage');
    }
    
}
