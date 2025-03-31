<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/user', name: 'admin_user_')] // Tous les noms de routes commenceront par 'admin_user_'
#[IsGranted('ROLE_ADMIN')]
final class UserController extends AbstractController
{
    // méthode pour afficher tous les utiisatuers exisatant
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    // methode de création d'un nouveau utilisateur
    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'l\'Utilisateur  est créé avec succès !');
            
            return $this->redirectToRoute('admin_user_index'); // Redirection corrigée
        }

        return $this->render('admin/user/new.html.twig', [ // Chemin au singulier
            'form' => $form->createView(),
        ]);
    } 
    // methode d'affichage des info d'un utilisateur
    
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }
    //methode pour éditer un utilisateur 
    #[Route ('/{id}/edit', name: 'edit',methods: ['GET', 'POST'])]
    public function edit(User $user,Request $request,EntityManagerInterface $entityManager):Response
    {
        //afficher le formulaire pour modifier les information d'un utilisateur
        $form = $this->createForm(UserType::class , $user);
        // creation de la soumission du formulaire
        $form ->handleRequest($request);
        // assure toi que le formulaire et juste
        if($form->isSubmitted() && $form->isValid())
        {
            // on souvgarde les modification soumis
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            //rediriger maintenant vers une autre page (exemple: la liste des utilisateurs)
        }
        // procéder à l'affihage du formulaire dans la page didier
         return  $this ->render('admin/user/edit.html.twig', [
            'form'=> $form->createView(), // il ne faut jamais oublier de mettre cette methdoe
            'user'=>$user
         ]);
    }
    #[Route ('/{id}/delete', name: 'delete',methods: ['POST'])]
    public function delete(User $user,UserRepository $userRepository,EntityManagerInterface $entityManager):Response
    {
      $user = $userRepository->find($user);// ici j'ai utilisé l'id , mais je peux utiliser $user et laissé symfony faire le taf

        if ($user) {
           // $entityManager->remove($user); on remplace cette ligne pour anonymiser les utilisateur pour etres 
           //supprimer plustard à une date qu'on fixera aprés.
           $user->anonymize();
            $entityManager->flush();
            
            $this->addFlash('success', 'Compte anonymisé (RGPD)');
    } else {
        $this->addFlash('error','Utilisateur non trouvable');
    }
        return $this->redirectToRoute('admin_user_index');
    }



}