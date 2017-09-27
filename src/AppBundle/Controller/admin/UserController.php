<?php

namespace AppBundle\Controller\admin;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * User контроллер
 * 
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Создание пользователя
     * 
     * @Route("/create")
     * 
     * @return Response
     */
    public function createAction(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername('Ли суши');
        $user->setEmail('chinaforever@mail.ru');
        
        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Сохранён новый объект с id = '.$user->getId());
    }
    
    /**
     * Удаление пользователя
     * 
     * @Route("/delete")
     * 
     * @return Response
     */
    public function deleteAction(int $entityId = 1): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(User::class)->find($entityId);
                
        if (!$entity) {
            throw $this->createNotFoundException(
                'No entity found for id '.$entityId
            );
        }
        
        $em->remove($entity);
        $em->flush();
        
        return $this->redirectToRoute('article_index');
    }
}
