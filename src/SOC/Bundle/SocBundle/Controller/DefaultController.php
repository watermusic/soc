<?php

namespace SOC\Bundle\SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('SOCSocBundle:Default:index.html.twig', array('name' => $name));
    }
}
