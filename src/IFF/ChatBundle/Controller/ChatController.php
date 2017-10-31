<?php

namespace IFF\ChatBundle\Controller;

use AppBundle\Entity\User;
use IFF\ChatBundle\Form\ChatType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\UserBundle\Model\UserManagerInterface;

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
        /** @var $userManager UserManagerInterface */
        $userManager = $this->get('fos_user.user_manager');
        
        $user = $userManager->createUser();
        $user->setEnabled(true);
        
        $data['error'] = '';
        
        $form = $this->createForm(ChatType::class);
        
        return $this->render('IFFChatBundle:Chat:index.html.twig', [
            'form' => $form->createView(),
            'error' => $data['error'],
        ]);
    }
}
