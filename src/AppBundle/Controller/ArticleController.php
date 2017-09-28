<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articles;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Article контроллер
 * 
 * @Route("article")
 */
class ArticleController extends Controller
{
    /**
     * Выводит на экран список всех статей
     * 
     * @Route("/", name="article_index")
     * @Route("/index")
     * 
     * @return Response
     */
    public function indexAction(): Response
    {
        $articles = $this->getDoctrine()
                ->getRepository(Articles::class)
                ->findAll();
        
        $pageTitle = 'Архив';
        
        return $this->render('article/index.html.twig', [
            'pageTitle' => $pageTitle,
            'articles' => $articles,
        ]);
    }
    
    /**
     * Просмотр конкретной статьи
     * 
     * @Route("/view")
     * 
     * @return Response
     */
    public function viewAction(int $entityId = 11): Response
    {
        $entity = $this->getDoctrine()
                ->getRepository(Articles::class)
                ->find($entityId);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Сущность с таким id не найдена : '.$entityId
            );
        }

        return new Response(
            'Нужный рецепт: '. $entity->getName()
        );
    }
    
    /**
     * Создание новой статьи
     * 
     * @Route("/create")
     * 
     * @return Response
     */
    public function createAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $article = new Articles();
        $article->setName('Яблочный пирог');
        $article->setContent('Смешать всё, что есть, добавить яблоки');
        
        $article->setDate(new DateTime('now'));
        $article->setAuthor('шеф-повар Чарли');
        
        $user = $this->getDoctrine()
                ->getRepository(Articles::class)
                ->find(11)
                ->getUser();
        
//        var_dump(get_class($user));
//        die('1234');
        
        $article->setUser($user);

        $em->persist($article);

        $em->flush();
        
        return new Response('Сохранён новый объект с id = '.$article->getId());
    }

    /**
     * Редактирование статьи
     * 
     * @Route("/update")
     * 
     * @param int $entityId
     * 
     * @return Response
     * 
     * @throws NotFoundException
     */
    public function updateAction(int $entityId = 4): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Articles::class)->find($entityId);

        if (!$entity) {
            throw $this->createNotFoundException(
                'No entity found for id '.$entityId
            );
        }

        $entity->setName('Картофельный гранж');
        $em->flush();

        return $this->redirectToRoute('article_index');
    }

    /**
     * Удаление статьи
     * 
     * @Route("/delete")
     * 
     * @return Response
     */
    public function deleteAction(int $entityId = 12): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository(Articles::class)->find($entityId);
                
        if (!$entity) {
            throw $this->createNotFoundException(
                'No entity found for id '.$entityId
            );
        }
        
        $em->remove($entity);
        $em->flush();
        
        return $this->redirectToRoute('article_index');
    }
    
    /**
     * Обработка 
     * 
     * @Route("/handler")
     */
    public function createFormHandlerAction()
    {
        $request = Request::createFromGlobals();

        echo $request->getPathInfo();

        $request->query->get('id');
        $request->request->get('category', 'default category');
        
        $pageTitle = 'Новый рецепт';

        return $this->render('article/create.html.twig', [
            'pageTitle' => $pageTitle,
            
        ]);
    }

}
