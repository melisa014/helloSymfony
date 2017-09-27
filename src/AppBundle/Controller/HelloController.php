<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HelloController extends Controller
{
    /**
     * @Route("/hello", name="hello_index")
     */
    public function indexAction()
    {
        return new Response(
            '<html><body>HelloWorld)</body></html>'
        );
    }
}
