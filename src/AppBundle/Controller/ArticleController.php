<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Articles;
use AppBundle\Entity\User;
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
        
//        $query = 'SELECT * FROM articles';
//        $pdo = new \PDO("pgsql:dbname=hello;host=127.0.0.1", "hellosymfony", "qwerty");
//        $pdo_articles = $pdo->prepare($query);
//        $result = $pdo_articles->execute();
//        
//        echo "<pre>";
//        print_r($result);
//        echo "</pre>";
//        die();
        
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
    public function viewAction(int $entityId = 2): Response
    {
        $entity = $this->getDoctrine()
                ->getRepository(Articles::class)
                ->find($entityId);

        if (!$entity) {
            throw $this->createNotFoundException(
                'Сущность с таким id не найдена : '.$entityId
            );
        }
        
//        var_dump($entity);
//        die('123');
        
//        $this->render();

        // ... do something, like pass the $product object into a template
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
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: createAction(EntityManagerInterface $em)
//        $em = $this->getDoctrine()->getManager();

//        $article = new Articles();
//        $article->setName('Яблочный пирог');
//        $article->setContent('Смешать всё, что есть, добавить яблоки');
//        
//        $article->setDate(new DateTime('now'));
//        $article->setAuthor('шеф-повар Чарли');
        
        
        
        
        $user = $this->getDoctrine()
                ->getRepository(Articles::class)
                ->find(11)
                ->getUser();
        
        var_dump($user);
        die('123');
        
        
        $article->setUser($user);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($article);

        // actually executes the queries (i.e. the INSERT query)
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
//        return $this->redirect('http://127.0.0.1:8000/article');
    }

    /**
     * Удаление статьи
     * 
     * @Route("/delete")
     * 
     * @return Response
     */
    public function deleteAction(int $entityId = 8): Response
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

//        echo "<pre>";
//        print_r($request);
//        echo "</pre>";
//        die('123');
        
        // the URI being requested (e.g. /about) minus any query parameters
        echo $request->getPathInfo();

        // retrieve $_GET and $_POST variables respectively
        $request->query->get('id');
        $request->request->get('category', 'default category');
        
        $pageTitle = 'Новый рецепт';

        return $this->render('article/create.html.twig', [
            'pageTitle' => $pageTitle,
            
        ]);
    }

}
