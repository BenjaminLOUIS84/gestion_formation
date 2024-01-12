<?php

namespace App\Controller;


// use App\Repository\CategorieRepository;
use App\Entity\Session;
use App\Form\SessionType;
use App\Repository\SessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController                              // AFFICHER LA LISTE DES SESSIONS
{
    #[Route('/session', name: 'app_session')]                                   // Route représentant l'URL '/session' pour la redirection et le name: sert pour la navigation

    public function index(SessionRepository $sessionRepository): Response       // Pour afficher la liste des Sessions insérer dans la fonction index() SessionRepository $sessionRepository
    {                                                                           // Importer la classe SessionRepository avec un click droit
        $sessions = $sessionRepository->findBy([], ["id" => "ASC"]);            // Pour récupérer la liste des sessions classées par ordre de création (id)
        
        return $this->render('session/index.html.twig', [                       // render() Permet de faire le lien entre le controller et la view 
            
            'sessions' => $sessions                                             // Pour passer la variable $sessions en argument 'sessions'
        ]);                                                                     // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }}
    }                                                                           // Dans le fichier index.html.twig du dossier session

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN BOUTON POUR SUPPRIMER UNE SESSION

    #[Route('/session/{id}/delete', name: 'delete_session')]                    // Reprendre la route en ajoutant /{id}/delete' à l'URL et en changeant le nom du name

    public function delete(Session $session, EntityManagerInterface $entityManager): Response   // Créer une fonction delete() dans le controller pour supprimer une session

    {     
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }

        $entityManager->remove($session);                                         // Supprime une session
        $entityManager->flush();                                                  // Exécute l'action DANS LA BDD

        return $this->redirectToRoute('app_session');                             // Rediriger vers la liste des sessions
       
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER UN FORMULAIRE POUR AJOUTER et EDITER DES SESSIONS

    #[Route('/session/new', name: 'new_session')]           // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
    #[Route('/session/{id}/edit', name: 'edit_session')]    // Reprendre la route en ajoutant /{id}/edit à l'URL et en changeant le nom du name

    public function new_edit(Session $session = null, Request $request, EntityManagerInterface $entityManager): Response   
    // Créer une fonction new() dans le controller pour permettre l'ajout de session et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création

    {
        if (!$this->isGranted('ROLE_ADMIN')) {                          // Permet d'empécher l'accès à cette action si ce n'est pas un admin
            throw $this->createAccessDeniedException('Accès non autorisé');
        }
        
        if(!$session){                                            // S'il n'ya pas de session à modifier alors en créer une nouvelle
            $session = new Session();                             // Après avoir importé la classe Request Déclarer une nouvelle session
        }

        $form = $this->createForm(SessionType :: class, $session);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe sessionType

        //////////////////////////////////////////////////////////////////////////
        //                                                                  GERER LE TRAITEMENT EN BDD
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $session = $form->getData();                        // Récupérer les informations de la nouvelle session 
            //prepare PDO
            $entityManager->persist($session);                  // Dire à Doctrine que je veux sauvegarder la nouvelle session           
            //execute PDO
            $entityManager->flush();                            // Mettre la nouvelle cate$session dans la BDD

            return $this->redirectToRoute('app_session');       // Rediriger vers la liste des sessions
        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('session/new.html.twig', [         // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier session)
            'form' => $form,                          // Pour nommer le formulaire
            'edit' => $session->getId(),

            'sessionId' => $session->getId()

        ]);
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LE DETAIL D'UNE SESSION

    #[Route('/session/{id}', name: 'show_session')]                             // Reprendre la route en ajoutant /{id} pour obtenir le détail d'un objet de session précise à l'URL et en changeant le nom du name

    public function show(Session $session, SessionRepository $sessionRepository): Response   // Créer une fonction show() dans le controller pour afficher le détail d'un session
    
    {         
        $id = $sessionRepository->find($session->getId());                      // Récupérer l'Id de la session pour que la variable ci dessous
        // $nonInscrits = $sessionRepository->findStagiairesNonInscrit($id);       // puisse trouver les non inscrits d'une session 
        // $nonProgrammes = $sessionRepository->findMatiereNonProgramme($id);      // Afficher la liste des modules non programmé
        $futurSessions = $sessionRepository->futur([], ["id" => "ASC"]);             // Afficher les sessions passées
       

        return $this->render('session/show.html.twig', [                        // Pour faire le lien entre le controller et la vue show.html.twig (il faut donc la créer dans le dossier session)
            'session' => $session,                                              
            // 'nonInscrits' => $nonInscrits,
            // 'nonProgrammes' => $nonProgrammes    
            'futurSessions' => $futurSessions                                              

        ]);
    }   

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LES SESSIONS PASSEES

    #[Route('/pastSessions', name: 'past_session')]                             // Reprendre la route en ajoutant /pastSessions pour obtenir un index des sessions passées à l'URL et en changeant le nom du name

    public function showPast( SessionRepository $sessionRepository): Response   // Créer une fonction showPast() dans le controller pour afficher le détail d'un session

    {                       
       $pastSessions = $sessionRepository->past([], ["id" => "ASC"]);           // Afficher les sessions passées
        
        return $this->render('session/past.html.twig', [                        // render() Permet de faire le lien entre le controller et la view 
            
            'pastSessions' => $pastSessions                                     // Pour passer la variable $pastSessions en argument 'pastSessions'
        ]);                                      
    }
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LES SESSIONS EN COURS

    #[Route('/presentSessions', name: 'present_session')]                               // Reprendre la route en ajoutant /presentSessions pour obtenir un index des sessions passées à l'URL et en changeant le nom du name

    public function showPresent( SessionRepository $sessionRepository): Response        // Créer une fonction showPresent() dans le controller pour afficher le détail d'un session

    {                       
       $presentSessions = $sessionRepository->present([], ["id" => "ASC"]);             // Afficher les sessions passées
        
        return $this->render('session/present.html.twig', [                             // render() Permet de faire le lien entre le controller et la view 
            
            'presentSessions' => $presentSessions                                       // Pour passer la variable $presentSessions en argument 'presentSessions'
        ]);                                      
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR AFFICHER LES SESSIONS A VENIR

    #[Route('/futurSessions', name: 'futur_session')]                                   // Reprendre la route en ajoutant /futurSessions pour obtenir un index des sessions passées à l'URL et en changeant le nom du name

    public function showFutur( SessionRepository $sessionRepository): Response          // Créer une fonction showFutur() dans le controller pour afficher le détail d'un session

    {                       
       $futurSessions = $sessionRepository->futur([], ["id" => "ASC"]);                 // Afficher les sessions passées
        
        return $this->render('session/futur.html.twig', [                               // render() Permet de faire le lien entre le controller et la view 
            
            'futurSessions' => $futurSessions                                           // Pour passer la variable $futurSessions en argument 'futurSessions'
        ]);                                      
    }

    
}