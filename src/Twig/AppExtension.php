<?php


namespace App\Twig;

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

    public function __construct(RegionRepository $regionRepository,DomaineRepository $domaineRepository,FormFactoryInterface $formFactory)
    {
        $this->regionRepository = $regionRepository;
        $this->domaineRepository = $domaineRepository;
        $this->formFactory = $formFactory;
        
    }

    public function getGlobals(): array
    {
        return [
             
            'regions' => $this->regionRepository->findAll(),
            'domaines'=> $this->domaineRepository->findAll() ,
            'formSearch' => $this->formFactory->create(SearchProductType::class)->createView(),
            'form' => $this->formFactory->create(SearchPriceType::class)->createView(),
           
            
        ];
    }
}