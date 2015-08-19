<?php
/**
 * Created by PhpStorm.
 * User: Bicker
 * Date: 29.07.2015
 * Time: 11:45
 */

namespace SOC\Bundle\SocBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ScoreAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('matchday', 'choice', array('label' => 'Spieltag', 'choices' => range(1, 34)))
            ->add('player', 'sonata_type_model_list', array('label' => 'Teilnehmer'))
            ->add('score', 'text', array('label' => 'Punkte'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('matchday')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('matchday')
            ->add('player')
            ->add('score')
        ;
    }

}