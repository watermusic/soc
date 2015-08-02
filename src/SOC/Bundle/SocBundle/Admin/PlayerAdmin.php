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

class PlayerAdmin extends Admin
{

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

//        $this->

        $formMapper
            ->add('name', 'text', array('label' => 'Name'))
            ->add('team', 'sonata_type_model_list')
            ->add('position', 'sonata_type_model_list')
            ->add('user', 'sonata_type_model_list', array('label' => 'Teilnehmer'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('team')
            ->add('position')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('team')
            ->add('position')
            ->add('punkte')
            ->add('user', null, array('editable' => true, 'label' => 'Käufer'))
            ->add('ek_preis', 'currency', array('editable' => true, 'currency' => 'EUR'))
            ->add('vk_preis', 'currency', array('currency' => 'EUR', 'label' => 'Listenpreis'))
        ;
    }

}