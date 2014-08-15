<?php

namespace SOC\Bundle\SocBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PlayerType extends AbstractType
{

    protected $statics;

    public function __construct($statics = array()) {
        $this->statics = $statics;
    }

        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $vereine = array();
        foreach($this->statics["vereine"] as $verein) {
            $vereine[$verein] = $verein;
        }

        $positionen = array();
        foreach($this->statics["positionen"] as $position) {
            $positionen[$position] = $position;
        }

        $players = array();
        foreach($this->statics["spieler"] as $player) {
            $key = $player;
            if($key === "- alle -") {
                $key = "";
            }
            $players[$key] = $player;
        }


        $builder
            ->add('name')
            ->add('verein', 'choice', array(
                    'choices'   => $vereine
                )
            )
            ->add('position', 'choice', array(
                    'choices'   => $positionen
                )
            )
            ->add('vkPreis', 'money')
            ->add('ekPreis', 'money')
            ->add('kaufer', 'choice', array(
                'choices'   => $players,
                'required'  => false,
                )
            )
            ->add('note', 'number')
            ->add('punkte', 'number')
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
