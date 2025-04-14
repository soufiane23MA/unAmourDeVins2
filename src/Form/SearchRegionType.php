<?php

 

namespace App\Form;

use App\Entity\Region;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchRegionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function($region) { return $region->getNomRegion(); }, $options['regions']),
                    array_map(function($region) { return $region->getId(); }, $options['regions'])
                ),
                'label' => 'Sélectionner une région',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'regions' => [], // Pour injecter les régions dans le formulaire
        ]);
    }
}
