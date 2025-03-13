<?php


namespace App\Form;

 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

 


class SearchProductType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
			$builder
					->add('query', SearchType::class, [
							'label' => false,
							'attr' => [
									'placeholder' => 'Rechercher un produit...',
							],
						]);
					 
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
			$resolver->setDefaults([
					// Configurez vos options ici si nécessaire
					'method' => 'GET', // Utiliser GET pour la soumission du formulaire
        'csrf_protection' => false, // Désactiver la protection CSRF pour les formulaires GET
			]);
	}
}

	

