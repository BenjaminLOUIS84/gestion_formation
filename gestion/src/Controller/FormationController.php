<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FormationRepository;                                         // La classe importée s'ajoute ici automatiquement

class FormationController extends AbstractController                            // AFFICHER LA LISTE DES FORMATIONS
{
    #[Route('/formation', name: 'app_formation')]                               // Route représentant l'URL '/formation' pour la redirection et le name: sert pour la navigation

    public function index(FormationRepository $formationRepository): Response   // Pour afficher la liste des formations insérer dans la fonction index() FormationRepository $formationRepository
    {                                                                           // Importer la classe FormationRepository avec un click droit 
        $formations = $formationRepository->findBy([], ["intitule" => "ASC"]);  // Pour récupérer la liste des formations classées par ordre alphabéthique selon l'intitulé

        return $this->render('formation/index.html.twig', [                     // render() Permet de faire le lien entre le controller et la view 

            'formations' => $formations                                         // Pour passer la variable $formations en argument 'formations'
        ]);                                                                     // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }} 
    }                                                                           // Dans le fichier index.html.twig du dossier formation

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN BOUTON POUR SUPPRIMER UNE FORMATION

    #[Route('/formation/{id}/delete', name: 'delete_formation')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Formation $formation, EntityManagerInterface $entityManager): Response   // Créer une fonction delete() dans le controller pour supprimer une formation

    {     
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $entityManager->remove($formation);                                         // Supprime une formation
        $entityManager->flush();                                                  // Exécute l'action DANS LA BDD

        return $this->redirectToRoute('app_formation');                             // Rediriger vers la liste des sessions
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN FORMULAIRE POUR AJOUTER et EDITER DES CATEGORIES

    #[Route('/formation/new', name: 'new_formation')]           // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/formation/{id}/edit', name: 'edit_formation')]    // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Formation $formation = null, Request $request, EntityManagerInterface $entityManager): Response   
    // Créer une fonction new() dans le controller pour permettre l'ajout de catégorie et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if (!$this->isGranted('ROLE_ADMIN')) {                 // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
        
        if(!$formation){                                        // S'il n'ya pas de catégorie à modifier alors en créer une nouvelle
            $formation = new Formation();                       // Après avoir importé la classe Request Déclarer une nouvelle catégorie
        }

        $form = $this->createForm(FormationType :: class, $formation);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe formationType

        //////////////////////////////////////////////////////////////////////////
        //                                                                  GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $formation = $form->getData();                      // Récupérer les informations de la nouvelle formation 
            //prepare PDO
            $entityManager->persist($formation);                // Dire à Doctrine que je veux sauvegarder la nouvelle formation           
            //execute PDO
            $entityManager->flush();                            // Mettre la nouvelle cate$formation dans la BDD

            return $this->redirectToRoute('app_formation');     // Rediriger vers la liste des formations
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('formation/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier formation)
            'formAddFormation' => $form,
            'edit' => $formation->getId()
        ]);
    }


    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL D'UNE FORMATION

    #[Route('/formation/{id}', name: 'show_formation')]                             // Reprendre la route en ajoutant /{id} pour obtenir le détail d'un objet de formation précise à l'URL et en changeant le nom du name

    public function show(Formation $formation, FormationRepository $formationRepository): Response   // Créer une fonction show() dans le controller pour afficher le détail d'un formation
    
    {         
        $id = $formationRepository->find($formation->getId());                      // Récupérer l'Id de la formation pour que la variable ci dessous

        return $this->render('formation/show.html.twig', [                        // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier formation)
            'formation' => $formation,                                                                                          

        ]);
    }   
}
