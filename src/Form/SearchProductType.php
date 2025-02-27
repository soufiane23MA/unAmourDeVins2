<?php

namespace App\Form;

 
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class SearchProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Prix', NumberType::class, [
                'label' => 'Prix maximum',
                'attr'=> [
                    'min'=> 0,
                    'max'=> 200,
                    'step'=> 5
                ],
            ])
            ->add('submit', SubmitType::class);
             
                
             
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             
        ]);
    }
    
   

}