<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

 


class SearchPriceType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
			$builder
					->add('query', SearchType::class, [
							'label' => false,
							'attr' => [
									'placeholder' => 'Rechercher un produit...',
							],
					])
					->add('rechercher', SubmitType::class, [
							'label' => 'Rechercher',
					]);
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
			$resolver->setDefaults([
					// Configurez vos options ici si nécessaire
			]);
	}
}

	

