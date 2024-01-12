<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\StagiaireRepository;                                         // La classe importée s'ajoute ici automatiquement

class StagiaireController extends AbstractController                            // AFFICHER LA LISTE DES STAGIAIRES
{
    #[Route('/stagiaire', name: 'app_stagiaire')]                               // Route représentant l'URL '/stagiaire' pour la redirection et le name: sert pour la navigation

    public function index(StagiaireRepository $stagiaireRepository): Response   // Pour afficher la liste des stagiaires insérer dans la fonction index() StagiaireRepository $stagiaireRepository
    {                                                                           // Importer la classe StagiaireRepository avec un click droit
        $stagiaires = $stagiaireRepository->findBy([], ["nom" => "ASC"]);       // Pour récupérer la liste des stagiaires classés par ordre alphabéthique selon le nom 
        
        return $this->render('stagiaire/index.html.twig', [                     // render() Permet de faire le lien entre le controller et la view 
            
            'stagiaires' => $stagiaires                                         // Pour passer la variable $stagiaires en argument 'stagiaires'
        ]);                                                                     // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }}
    }                                                                           // Dans le fichier index.html.twig du dossier stagiaire

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN BOUTON POUR SUPPRIMER UN STAGIAIRE

    #[Route('/stagiaire/{id}/delete', name: 'delete_stagiaire')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response   // Créer une fonction delete() dans le controller pour supprimer un stagiaire

    {                                                                               // Importer la classe Stagiaire
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        // if($this->getUser() == $stagiaire->getUser() || $this->isGranted('ROLE_ADMIN') == true)                                 
        // {

            $entityManager->remove($stagiaire);                                         // Supprime un stagiaire 
            $entityManager->flush();                                                    // Exécute l'action DANS LA BDD

            return $this->redirectToRoute('app_stagiaire');                             // Rediriger vers la liste des stagiaires
        
        // }else{
        //     throw $this->createAccessDeniedException('Accès non autorisé');             // Sinon on interdit l'accès
        // }

    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN FORMULAIRE POUR AJOUTER DES STAGIAIRES

    #[Route('/stagiaire/new', name: 'new_stagiaire')]         // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/stagiaire/{id}/edit', name: 'edit_stagiaire')]  // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(

        Stagiaire $stagiaire = null, 
        Request $request, 
        EntityManagerInterface $entityManager
        
    ): Response  
     
    // Créer une fonction new() dans le controller pour permettre l'ajout de stagiaires et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {    
        // if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
        //     throw $this->createAccessDeniedException('Accès non autorisé');
        // }

        // if($this->getUser() == $stagiaire->getUser() || $this->isGranted('ROLE_ADMIN') == true)                                 
        // {
              
            if(!$stagiaire){                                      // S'il n'y a pas de stagiaires à modifier alors créer un nouveau stagiaire                                  
            $stagiaire = new Stagiaire();                         // Après avoir importé la classe Request Déclarer un nouveau stagiaire
            }

            $form = $this->createForm(StagiaireType :: class, $stagiaire);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe StagiaireType

        // }else{
        //     throw $this->createAccessDeniedException('Accès non autorisé');             // Sinon on interdit l'accès
        // }

        //////////////////////////////////////////////////////////////////////////
        //                                                                  GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $stagiaire = $form->getData();                      // Récupérer les informations du nouveau stagiaire 
            //prepare PDO
            $entityManager->persist($stagiaire);                // Dire à Doctrine que je veux sauvegarder le nouveau stagiaire           
            //execute PDO
            $entityManager->flush();                            // Mettre le nouveau stagiaire dans la BDD

            return $this->redirectToRoute('app_stagiaire');     // Rediriger vers la liste des categories
        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('stagiaire/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier stagiaire)
            'formAddStagiaire' => $form,
            'edit' => $stagiaire->getId()
        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL D'UN STAGIAIRE

    #[Route('/stagiaire/{id}', name: 'show_stagiaire')]                         // Reprendre la route en ajoutant /{id} à l'URL et en changeant le nom du name

    public function show(Stagiaire $stagiaire): Response                        // Créer une fonction show() dans le controller pour afficher le détail d'un stagiaire

    {                                                                           // Importer la classe Stagiaire
        return $this->render('stagiaire/show.html.twig', [                      // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier stagiaire)
            'stagiaire' => $stagiaire
        ]);
    }

}
