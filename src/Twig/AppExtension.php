<?php


namespace App\Twig;

use App\Repository\DomaineRepository;
use App\Repository\RegionRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $regionRepository;
    private $domaineRepository;

    public function __construct(RegionRepository $regionRepository,DomaineRepository $domaineRepository)
    {
        $this->regionRepository = $regionRepository;
        $this->domaineRepository = $domaineRepository;
    }

    public function getGlobals(): array
    {
        return [
             
            'regions' => $this->regionRepository->findAll(),
            'domaines'=> $this->domaineRepository->findAll()        ];
    }
}