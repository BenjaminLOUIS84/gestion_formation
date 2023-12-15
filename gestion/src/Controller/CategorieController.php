<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;                                       // La classe importée s'ajoute ici automatiquement

class CategorieController extends AbstractController
{                                                                             // AFFICHER LA LISTE DES CATEGORIES
    #[Route('/categorie', name: 'app_categorie')]                             // Route représentant l'URL '/categorie' pour la redirection et le name: sert pour la navigation

    public function index(CategorieRepository $categorieRepository): Response // Pour afficher la liste des catégories insérer dans la fonction index() CategorieRepository $categorieRepository 
    {                                                                         // Importer la classe CategorieRepository avec un click droit 
        $categories = $categorieRepository->findBy([], ["titre" => "ASC"]);   // Pour récupérer la liste des catégories classées par ordre alphabéthique selon le titre

        return $this->render('categorie/index.html.twig', [                   // render() Permet de faire le lien entre le controller et la view 
        
            'categories' => $categories                                       // Pour passer la variable $categories en argument 'categories'
        ]);                                                                   // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} 
    }                                                                         // Dans le fichier index.html.twig du dossier categorie

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN BOUTON POUR SUPPRIMER UNE CATEGORIE

    #[Route('/categorie/{id}/delete', name: 'delete_categorie')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Categorie $categorie, EntityManagerInterface $entityManager): Response   // Créer une fonction delete() dans le controller pour supprimer une categorie

    {                                                                               
        $entityManager->remove($categorie);                                         // Supprime une catégorie
        $entityManager->flush();                                                    // Exécute l'action DANS LA BDD

        return $this->redirectToRoute('app_categorie');                             // Rediriger vers la liste des categories
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN FORMULAIRE POUR AJOUTER et EDITER DES CATEGORIES

    #[Route('/categorie/new', name: 'new_categorie')]           // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/categorie/{id}/edit', name: 'edit_categorie')]    // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Categorie $categorie = null, Request $request, EntityManagerInterface $entityManager): Response   
    // Créer une fonction new() dans le controller pour permettre l'ajout de catégorie et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if(!$categorie){                                        // S'il n'ya pas de catégorie à modifier alors en créer une nouvelle
            $categorie = new Categorie();                       // Après avoir importé la classe Request Déclarer une nouvelle catégorie
        }

        $form = $this->createForm(CategorieType :: class, $categorie);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe CategorieType

        //////////////////////////////////////////////////////////////////////////
        //                                                                  GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $categorie = $form->getData();                      // Récupérer les informations de la nouvelle categorie 
            //prepare PDO
            $entityManager->persist($categorie);                // Dire à Doctrine que je veux sauvegarder la nouvelle categorie           
            //execute PDO
            $entityManager->flush();                            // Mettre la nouvelle cate$categorie dans la BDD

            return $this->redirectToRoute('app_categorie');     // Rediriger vers la liste des categories
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('categorie/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier categorie)
            'formAddCategorie' => $form,
            'edit' => $categorie->getId()
        ]);
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL D'UNE CATEGORIE

    #[Route('/categorie/{id}', name: 'show_categorie')]                             // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(Categorie $categorie): Response                            // Créer une fonction show() dans le controller pour afficher le détail d'un categorie

    {                                                                               // Importer la classe Categorie
        return $this->render('categorie/show.html.twig', [                          // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier categorie)
            'categorie' => $categorie
        ]);
    }
}

