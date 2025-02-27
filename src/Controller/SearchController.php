<?php

namespace App\Controller;
 
use App\Form\SearchPriceType;
use App\Form\SearchProductType;
use App\Repository\ProduitRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class SearchController extends AbstractController {
    #[Route('/search', name: 'app_search')]
    public function index(ProduitRepository $pr, Request $request): Response
    {
        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);
       

        if($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();  
            $produits = $pr->findByPriceMax($data);
          
           

            return $this->render('search/index.html.twig',[
                'produits'=>$produits
            ]);
        }

        return $this->render('base.html.twig',[
            'formSearch'=>$form,
            'test' => 'mon test'
        ]);
    }

}

 
