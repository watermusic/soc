<?php

namespace SOC\Bundle\SocBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use SOC\Bundle\SocBundle\Entity\Player;
use SOC\Bundle\SocBundle\Form\PlayerType;

/**
 * Player controller.
 *
 */
class PlayerController extends Controller
{

    /**
     * Lists all Player entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get("request");

        $query = $this->getQuery();

//        $entities = $em->getRepository('SOCSocBundle:Player')->findAll();

        $criteria = array();
        foreach($query as $name => $val) {

            if($val === null || $val === "" || $val === '- alle -') {
                continue;
            }
            $criteria[$name] = $val;
        }

        $entities = $em->getRepository('SOCSocBundle:Player')->findBy($criteria, array('vkPreis' => 'DESC'));

        return $this->render('SOCSocBundle:Player:index.html.twig', array(
            'entities' => $entities,
            'statics' => $this->getStaticViewParameter(),
            "query" => $query,
        ));
    }
    /**
     * Creates a new Player entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Player();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('player_show', array('id' => $entity->getId())));
        }

        return $this->render('SOCSocBundle:Player:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'statics' => $this->getStaticViewParameter(),
            "query" => $this->getQuery(),
        ));
    }

    /**
     * Creates a form to create a Player entity.
     *
     * @param Player $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Player $entity)
    {
        $form = $this->createForm(new PlayerType(), $entity, array(
            'action' => $this->generateUrl('player_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Player entity.
     *
     */
    public function newAction()
    {
        $entity = new Player();
        $form   = $this->createCreateForm($entity);

        return $this->render('SOCSocBundle:Player:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'statics' => $this->getStaticViewParameter(),
            "query" => $this->getQuery(),
        ));
    }

    /**
     * Finds and displays a Player entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SOCSocBundle:Player')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Player entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SOCSocBundle:Player:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
            'statics' => $this->getStaticViewParameter(),
            "query" => $this->getQuery(),
        ));
    }

    /**
     * Displays a form to edit an existing Player entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SOCSocBundle:Player')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Player entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('SOCSocBundle:Player:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'statics' => $this->getStaticViewParameter(),
            "query" => $this->getQuery(),
        ));
    }

    /**
    * Creates a form to edit a Player entity.
    *
    * @param Player $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Player $entity)
    {

        $statics = $this->getStaticViewParameter();

        $form = $this->createForm(new PlayerType($statics["spieler"]), $entity, array(
            'action' => $this->generateUrl('player_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Player entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SOCSocBundle:Player')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Player entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('player_edit', array('id' => $id)));
        }

        return $this->render('SOCSocBundle:Player:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'statics' => $this->getStaticViewParameter(),
            "query" => $this->getQuery(),
        ));
    }
    /**
     * Deletes a Player entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SOCSocBundle:Player')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Player entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('player'));
    }

    /**
     * Search a Soc entity
     *
     */
    public function searchAction() {

        error_reporting(E_ALL);
        ini_set("display_errors", "1");

        $request = $this->get("request");
        $search = $request->get('q');

        $em = $this->getDoctrine()->getManager();
        $qb = $em->getRepository('SOCSocBundle:Player')->createQueryBuilder('p');
        $query = $qb->where(
            $qb->expr()->like('p.name', ':search')
        )
            ->setParameter('search', '%' . $search . '%')
            ->getQuery();

        $entities = $query->getResult();

        $result = array();
        foreach ($entities as $entity) {
            $result[] = array(
                "name" => $entity->getName()
            );
        }

        return new JsonResponse($result);

    }

    /**
     * Creates a form to delete a Player entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('player_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }

    /**
     * @return array
     */
    private function getStaticViewParameter() {

        $players = $this->container->getParameter("soc_player");
        /**
         * @var $conn \Doctrine\DBAL\Connection
         */
        $conn = $this->getDoctrine()->getConnection();


        $sql = "SELECT DISTINCT verein FROM Player ORDER BY verein ASC";
        $stmt = $conn->executeQuery($sql);
        $res = $stmt->fetchAll();

        $vereine = array("- alle -");
        foreach($res as $val) {
            array_push($vereine, $val["verein"]);
        }

        $result = array();
        $result["positionen"] = array(
            "- alle -",
            "Torwart",
            "Abwehr",
            "Mittelfeld",
            "Sturm",
        );
        $result["spieler"] = $players;
        $result["vereine"] = $vereine;

        return $result;
    }


    private function getQuery() {

        $request = $this->get("request");

        $query = array();
        $query['kaufer'] = $request->get("kaufer", null);
        $query['verein'] = $request->get("verein", null);
        $query['position'] = $request->get("position", null);
        $query['name'] = $request->get("name", null);

        return $query;
    }

}
