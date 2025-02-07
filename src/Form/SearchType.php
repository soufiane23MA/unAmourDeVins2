<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Region;
use App\Entity\Domaine;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('region', EntityType::class, [
                'class' => Region::class,
                'choice_label' => 'nomRegion',
                'placeholder' => 'Choisir une région',
                'required' => false,
            ])
            ->add('domaine', EntityType::class, [
                'class' => Domaine::class,
                'choice_label' => 'nomDomaine',
                'placeholder' => 'Choisir un domaine',
                'required' => false,
            ])
            ->add('prixMin', NumberType::class, [
                'label' => 'Prix minimum',
                'required' => false,
            ])
            ->add('prixMax', NumberType::class, [
                'label' => 'Prix maximum',
                'required' => false,
            ]);
    }
}