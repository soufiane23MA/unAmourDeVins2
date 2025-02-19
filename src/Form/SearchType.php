<?php

namespace App\Form;

 
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
 
 

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    { 
        $builder
        ->add('Prix', RangeType::class, [
            'label'=> 'filtrer par prix',
            'attr' => [
                'min' => 5,
                'max' => 200,
            'step'=> 5,// c'est le rajout en euro quand le curseur bouge, "l'incrémentation"
            'oninput'=> 'this.nextElementSibling.value = this.value', // Mise à jour en temps réel avec javascript 
             ]
        ]);
        
    }
    public function configureOptions(OptionsResolver $resolver): void// cherche le role de resolver
	{
			$resolver->setDefaults([
					' method'=> 'GET'
			]);
	}
}