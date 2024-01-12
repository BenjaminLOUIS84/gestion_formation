<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;

class MatiereController extends AbstractController                              // AFFICHER LA LISTE DES MATIERES
{
    #[Route('/matiere', name: 'app_matiere')]                                   // Route représentant l'URL '/Matiere' pour la redirection et le name: sert pour la navigation

    public function index( Session $session, MatiereRepository $matiereRepository, SessionRepository $sessionRepository): Response       // Pour afficher la liste des Matieres insérer dans la fonction index() MatiereRepository $matiereRepository
    {                                                                           // Importer la classe MatiereRepository avec un click droit
        $matieres = $matiereRepository->findBy([], ["denomination" => "ASC"]);  // Pour récupérer la liste des Matieres classés par ordre alphabéthique selon la dénomination 
        
        // Afficher la liste des modules classé par ordre alphabétique selon le titre de la catégorie
        //$matieres = $matiereRepository->findBy([], ["denomination" => "ASC"]); // Pour récupérer la liste des Matieres classés par ordre alphabéthique selon la dénomination 

        return $this->render('matiere/index.html.twig', [                       // render() Permet de faire le lien entre le controller et la view 
            
            'matieres' => $matieres,                                            // Pour passer la variable $matieres en argument 'matieres'
            
        ]);                                                                     // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }}
    }                                                                           // Dans le fichier index.html.twig du dossier matiere

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN BOUTON POUR SUPPRIMER UN MODULE

    #[Route('/matiere/{id}/delete', name: 'delete_matiere')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Matiere $matiere, EntityManagerInterface $entityManager): Response   // Créer une fonction delete() dans le controller pour supprimer une matiere

    {      
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
                                                                                 
        $entityManager->remove($matiere);                                       // Supprime une matiere
        $entityManager->flush();                                                // Exécute l'action DANS LA BDD
        return $this->redirectToRoute('app_matiere');                           // Rediriger vers la liste des modules
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN FORMULAIRE POUR AJOUTER et EDITER DES MODULES

    #[Route('/matiere/new', name: 'new_matiere')]           // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/matiere/{id}/edit', name: 'edit_matiere')]    // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new(Matiere $matiere = null, Request $request, EntityManagerInterface $entityManager): Response   
    // Créer une fonction new() dans le controller pour permettre l'ajout de matière et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        if(!$matiere){                                      // S'il n'ya pas de matière à modifier alors en créer une nouvelle
            $matiere = new Matiere();                       // Après avoir importé la classe Request Déclarer une nouvelle matière
        }

        $form = $this->createForm(MatiereType :: class, $matiere);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe matiereType

        //////////////////////////////////////////////////////////////////////////
        //                                                                  GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {       // Si le formulaire soumis est valide alors
            
            $matiere = $form->getData();                      // Récupérer les informations de la nouvelle matiere 
            //prepare PDO
            $entityManager->persist($matiere);                // Dire à Doctrine que je veux sauvegarder la nouvelle matiere           
            //execute PDO
            $entityManager->flush();                          // Mettre la nouvelle matiere dans la BDD

            return $this->redirectToRoute('app_matiere');     // Rediriger vers la liste des catégories
        }

        //////////////////////////////////////////////////////////////////////////


        return $this->render('matiere/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier matiere)
            'formAddMatiere' => $form,
            'edit' => $matiere->getId()
        ]);
    }

}
