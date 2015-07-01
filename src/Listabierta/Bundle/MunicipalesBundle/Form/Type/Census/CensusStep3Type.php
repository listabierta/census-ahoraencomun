<?php

namespace Listabierta\Bundle\MunicipalesBundle\Form\Type\Census;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Listabierta\Bundle\MunicipalesBundle\Validator\Constraints\DNI;
use Listabierta\Bundle\MunicipalesBundle\Entity\ProvinceRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CensusStep3Type extends AbstractType
{
	public function __construct()
	{
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array(
        				'required' => true, 
        				'constraints' => array(
        					new Assert\NotBlank(),
        					new Assert\Length(array(
        							'min'        => 2,
        							'max'        => 255,
        							'minMessage' => 'Your first name must be at least {{ limit }} characters long',
        							'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters long',
        					))
        				)))
        	    ->add('lastname', 'text', array(
        	    		'required' => true, 
        				'constraints' => array(
        					new Assert\NotBlank(),
        					new Assert\Length(array(
        							'min'        => 2,
        							'max'        => 255,
        							'minMessage' => 'Your lastname must be at least {{ limit }} characters long',
        							'maxMessage' => 'Your lastname cannot be longer than {{ limit }} characters long',
        					))
        				)))
        	    ->add('dni', 'text', array(
        	    		'required' => true, 
        				'constraints' => array(
        					new Assert\NotBlank(),
        					new DNI(),
        				)))
        	    ->add('email', 'email', array(
        	    		'required' => true, 
        				'constraints' => array(
        					new Assert\NotBlank(),
        					new Assert\Email(),
        				)))
        	    ->add('phone', 'text', array(
        	    		'required' => true, 
        				'constraints' => array(
        					new Assert\NotBlank(),
        					new Assert\Length(array(
        						'min'        => 9,
        						'max'        => 12,
        						'minMessage' => 'Your phone must be at least {{ limit }} characters long',
        						'maxMessage' => 'Your phone cannot be longer than {{ limit }} characters long',
        						))
        				)))
	            ->add('continue', 'submit');
    }
	
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver->setDefaults(
    			array(
    					'allow_extra_fields' => true
    			)
    	);
    }

    public function getName()
    {
        return 'census_step3';
    }
}