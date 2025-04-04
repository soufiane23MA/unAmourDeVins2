<?php

namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')] // se controlleur gére juste l'affichage de dashbord pour le ADMIN 
final class AdminController extends AbstractController{
    #[Route('/admin/dashbord', name: 'admin_admin_dashbord')]
    public function index(): Response
    {
        return $this->render('admin/admin/dashbord.html.twig', [
            
        ]);
    }
}
