<?php
/**
 * Created by PhpStorm.
 * User: Bicker
 * Date: 04.08.2015
 * Time: 16:02
 */

namespace SOC\Bundle\SocBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\Validator\ConstraintViolationList;

class LineupRestController extends FOSRestController
{

    /**
     * Return the overall user list.
     *
     * @return View
     */
    public function getLineupsAction()
    {
        $lineupRepository = $this->getDoctrine()->getRepository('SOCSocBundle:Lineup');
        $entity = $lineupRepository->findAll();
        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }
        $view = View::create();
        $view->setData($entity)->setStatusCode(200);
        return $view;
    }

}