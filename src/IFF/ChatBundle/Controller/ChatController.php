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
            'anyUsers' => '',
            'sendMessage' => '',
            'showMessages' => '',
        ];
        
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(User::class)->findAll();
        
//        echo "<pre>";
//        print_r($users[0]);
//        echo "<br>";
//        print_r($users[1]);
//        echo "</pre>";

        if (empty($users)) {
            $errors['anyUsers'] = 'У Вас пока нет друзей';
        }
        
        $form = $this->createForm(ChatType::class);
        
        return $this->render('IFFChatBundle:Chat:index.html.twig', [
            'form' => $form->createView(),
            'error' => $errors,
            'users' => $users,
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
        $toUser = $request->get('toUser');
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
     * @Route("/test")
     */
    public function testAction()
    {
        return new Response("hihi!)");
    }
}
