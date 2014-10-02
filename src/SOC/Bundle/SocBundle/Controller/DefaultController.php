<?php

namespace SOC\Bundle\SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $view = array(
            'title' => $this->container->getParameter("soc_site_title"),
            'name' => $name,
        );

        return $this->render('SOCSocBundle:Default:index.html.twig', $view);
    }
}
