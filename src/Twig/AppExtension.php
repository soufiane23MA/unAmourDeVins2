<?php


namespace App\Twig;

use App\Repository\RegionRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $regionRepository;

    public function __construct(RegionRepository $regionRepository)
    {
        $this->regionRepository = $regionRepository;
    }

    public function getGlobals(): array
    {
        return [
            'regions' => $this->regionRepository->findAll(),
        ];
    }
}