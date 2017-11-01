<?php

namespace IFF\ChatBundle\Controller;

use AppBundle\Entity\User;
use IFF\ChatBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
    
    public function saveSendedMessages()
    {
        
    }
    
    public function loadMessages()
    {
        $errors['showMessages'] = '';
        
        $messages = [];
        
        
        
        
        
        if (empty($messages)) {
            $errors['showMessages'] = 'Здесь ещё нет ни одного сообщения';
        }
        
        return JsonResponse(json_encode([
            'messages' => $messages,
            'error' => $errors,
        ]));
    }
}
