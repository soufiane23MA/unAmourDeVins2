<?php

// src/Form/SearchDomaineType.php
namespace App\Form;

use App\Entity\Domaine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchDomaineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('domaine', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function($domaine) { return $domaine->getNomDomaine(); }, $options['domaines']),
                    array_map(function($domaine) { return $domaine->getId(); }, $options['domaines'])
                ),
                'label' => 'Sélectionner un domaine',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'domaines' => [], // Pour injecter les domaines dans le formulaire
        ]);
    }
}
