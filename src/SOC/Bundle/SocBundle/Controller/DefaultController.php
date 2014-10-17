<?php

namespace SOC\Bundle\SocBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SOCSocBundle:Score')->find(1);

        var_dump($entity->getPlayer());

        $view = array(
            'name' => 'hakan',
        );

        return $this->render('SOCSocBundle:Default:index.html.twig', $view);
    }
}
