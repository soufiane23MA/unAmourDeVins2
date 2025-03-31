<?php
namespace App\Form ;

use App\Entity\Domaine;
use App\Entity\Produit;
use Symfony\Component\Form\AbstractType;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ProduitType  extends AbstractType  // Hérite de AbstractType
{
	// c'est le formulaire du CRUD de la classe Admin
	public function buildForm(FormBuilderInterface $builder, array $options): void
	{
			$builder
					->add('image',  FileType::class, [
							'label' => 'image',
							'mapped' => false,
							'required' => false,
							'constraints' => [
        new File([
            'maxSize' => '2M', // Limite la taille du fichier
            'mimeTypes' => ['image/jpg', 'image/png', 'image/webp'],
            'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, WEBP)',
        ])
    ],
					])
					->add('nomProduit', TextType::class, [
							'label' => 'Nom du produit',
					])
					->add('domaine',EntityType ::class, [
						'class'=> Domaine::class,
						'choice_label'=>'nomDomaine',
						'attr' => ['class' => 'form-control'],
						 
						'required' => false,
				])
					->add('prix', NumberType::class, [
							'label' => 'Prix',
					])
					->add('contenance',TextType ::class, [
							'label'=> 'contenance  cl',
							'required' => false,
					])
					->add('detailProduit', TextareaType::class, [
							'label' => 'Description',
					])
					->add('exclusif', CheckboxType::class, [
						'label' => 'exclusif',
						'required' => false,
				])
					->add('submit', SubmitType::class, [
							'label' => 'Sauvegarder les modifications',
					])
					
					;
	}

	public function configureOptions(OptionsResolver $resolver): void
	{
			$resolver->setDefaults([
					'data_class' => Produit::class,
			]);
	}
}
