<?php

namespace IFF\ChatBundle\Controller;

use AppBundle\Entity\User;
use DateTime;
use IFF\ChatBundle\Entity\Message;
use IFF\ChatBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
            'sendMessage' => '',
            'showMessages' => '',
        ];
        $em = $this->getDoctrine()->getManager();
        
        // Достаём из БД всех друзей активного пользователя
        $activeUser = $this->getUser();
        $friends = $em->getRepository(User::class)->findBy(['id' => 14]);
        
        // Это закоментированно, пока не создано поле friends для юзеров
//        $friends = [];
//        foreach ($activeUser->getFriends() as $friendId) {
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
     * @Route("/loading")
     * 
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function loadMessagesAction(Request $request): JsonResponse
    {
        $errors['showMessages'] = '';
        
        $messages = [];
        
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(Message::class)->findBy([
            'user_id' => $data['user_id'],
            
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
     * @Route("/add_friend")
     * 
     * @param int $friendId
     */
    public function addFriend(int $friendId)
    {
        $activeUser = $this->getUser();
        $activeUser->addFriend($friendId);
    }
    
    /**
     * @Route("/add")
     */
    public function add()
    {
        $activeUser = $this->getUser();
        $activeUser->addFriend(20);
        
        echo "<pre>";
        print_r($activeUser->getFriends());
        echo "</pre>";
        die('tttt');


        return $this->redirectToRoute('homepage');
    }
    
    /**
     * @Route("/remove_friend")
     * 
     * @param int $friendId
     */
    public function removeFriend(int $friendId)
    {
        $activeUser = $this->getUser();
        $activeUser->removeFriend($friendId);
        
        return new $this->redirectToRoute('homepage');
    }
    
}
