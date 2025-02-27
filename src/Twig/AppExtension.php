<?php


namespace App\Twig;

use App\Repository\DomaineRepository;
use App\Repository\RegionRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Symfony\Component\Form\FormFactoryInterface;
use App\Form\SearchProductType;


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
            
        ];
    }
}