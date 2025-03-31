<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OrderController extends AbstractController
{
    #[Route('/admin/commande', name: 'app_admin_commande')]
    public function index(): Response
    {
        return $this->render('admin/commande/index.html.twig', [
            'controller_name' => 'AdminCommandeController',
        ]);
    }
}
