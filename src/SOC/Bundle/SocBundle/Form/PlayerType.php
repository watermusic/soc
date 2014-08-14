<?php

namespace SOC\Bundle\SocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerType extends AbstractType
{

    protected $players;

    public function __construct($players = array()) {
        $this->players = $players;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('name')
            ->add('verein')
            ->add('position')
            ->add('vkPreis')
            ->add('ekPreis')
            ->add('kaufer', 'choice', array(
                'choices'   => $this->players
                )
            )
            ->add('note')
            ->add('punkte')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SOC\Bundle\SocBundle\Entity\Player'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'soc_bundle_socbundle_player';
    }
}
