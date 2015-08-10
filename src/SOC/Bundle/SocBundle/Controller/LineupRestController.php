<?php
/**
 * Created by PhpStorm.
 * User: Bicker
 * Date: 04.08.2015
 * Time: 16:02
 */

namespace SOC\Bundle\SocBundle\Controller;

use SOC\Bundle\SocBundle\Entity\Lineup;
use SOC\Bundle\SocBundle\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * @Route("/lineup")
 */
class LineupRestController extends Controller
{

    /**
     * Return the overall user list.
     *
     * @Route("/{username}/{id}",  requirements={"id" = "\d+"}, defaults={"id" = 1})
     * @Method({"GET"})
     *
     * @param string $username
     * @param $id
     * @return JsonResponse
     */
    public function getLineupAction($username, $id)
    {
        $lineupRepository = $this->getDoctrine()->getRepository('SOCSocBundle:Lineup');
        $userRepository = $this->getDoctrine()->getRepository('SOCSocBundle:User');

        $user = $userRepository->findOneBy(array('username' => $username));

        if ($user === null) {
            throw $this->createNotFoundException('User not found.');
        }

        $criteria = array(
            'user' => $user,
            'matchday' => $id,
        );
        $entity = $lineupRepository->findOneBy($criteria);

        if (!$entity) {
            throw $this->createNotFoundException('Data not found.');
        }

        $data = $this->get('jms_serializer')->serialize($entity, 'json');
        $status = 200;

        return new Response($data, $status, array('Content-Type' => 'application/json'));

    }

}