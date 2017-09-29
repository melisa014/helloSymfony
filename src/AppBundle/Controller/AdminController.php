<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * User контроллер
 * 
 * @Route("user")
 */
class AdminController extends Controller
{
    /**
     * Корректировака данных пользователя
     * 
     * @Route("/")
     */
    public function updateAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        	
        $user = $userManager->createUser();
        
        var_dump(get_class($user));
        die('1234');
        

    }
}
