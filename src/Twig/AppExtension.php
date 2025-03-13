<?php


namespace App\Twig;

use Twig\Environment;
use Twig\TwigFunction;
use App\Form\SearchPriceType;
use App\Form\SearchProductType;
use App\Repository\RegionRepository;
use Twig\Extension\GlobalsInterface;
use App\Repository\DomaineRepository;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Form\FormFactoryInterface;


class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $regionRepository;
    private $domaineRepository;
    private $formFactory;
    private $twig; // <-- Ajoute cette propriété

    public function __construct(RegionRepository $regionRepository,DomaineRepository $domaineRepository,FormFactoryInterface $formFactory,Environment $twig )
    {
        
        $this->regionRepository = $regionRepository;
        $this->domaineRepository = $domaineRepository;
        $this->formFactory = $formFactory;
        $this->twig = $twig;
    }
    public function getGlobals(): array
    {
        return [
             
            'regions' => $this->regionRepository->findAll(),
            'domaines'=> $this->domaineRepository->findAll() ,
           // 'formSearch' => $this->formFactory->create(SearchProductType::class)->createView(),
            //'form' => $this->formFactory->create(SearchPriceType::class)->createView(),  
        ];
    }
    public function getFunctions(): array
    {
        return [
            new TwigFunction('render_search_product_form', [$this, 'renderSearchProductForm'], [
                'is_safe' => ['html'],
            ]),
           /* new TwigFunction('render_search_price_form', [$this, 'renderSearchPriceForm'], [
                'is_safe' => ['html'],
            ]),*/
        ];
    }
    public function renderSearchProductForm(): string
    {
        $form = $this->formFactory->create(SearchProductType::class);
      // Rend le formulaire en utilisant le template partiel
      return $this->twig->render('partials/_search_product_form.html.twig', [
        'form' => $form->createView(), // <-- Passe la vue du formulaire au template
    ]);
    }

    /*public function renderSearchPriceForm(): string
    {
        $form = $this->formFactory->create(SearchPriceType::class);
        return $this->formFactory->createView($form);
    }*/
}