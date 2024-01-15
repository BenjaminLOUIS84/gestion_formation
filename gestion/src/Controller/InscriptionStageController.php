<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Form\StagiaireType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionStageController extends AbstractController
{
    #[Route('/inscription/stage', name: 'app_inscription_stage')]
    public function index(): Response
    {
        return $this->render('inscription_stage/index.html.twig', [
            'controller_name' => 'InscriptionStageController',
        ]);
    }

     //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FONCTION POUR Une demande d'admission

    #[Route('inscription/stage/new', name: 'new_inscription_stage')]         // Reprendre la route en ajoutant /new à l'URL et en changeant le nom du name
   
    public function new(

        Stagiaire $stagiaire = null, 
        Request $request, 
        EntityManagerInterface $entityManager,
        MailerInterface $mailer
        
    ): Response  
     
    // Créer une fonction new() dans le controller pour permettre l'ajout de stagiaires et modifier celle-ci en new_edit pour permettre la modfication ou à défaut la création
    {    
            
        $stagiaire = new Stagiaire();                         // Après avoir importé la classe Request Déclarer un nouveau stagiaire
        $form = $this->createForm(StagiaireType :: class, $stagiaire);  // Créer un nouveau formulaire avec la méthode createForm() et importer le classe StagiaireType
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {         // Si le formulaire soumis est valide alors
            
            $stagiaire = $form->getData();                      // Récupérer les informations du nouveau stagiaire 
            $session = $form->getData();                      // Récupérer les informations du nouveau stagiaire 
            

            $email = (new TemplatedEmail())
            ->from($form->getData()->getMail())
            ->to('benlouisdevweb@gmail.com')
            ->subject('Demande d\'admission en formation')
            ->htmlTemplate('email/inscription.html.twig')
            ->context(compact('stagiaire','session'));

            $mailer->send($email);

            $this->addFlash('message', 'Inscription en attente de validation');
            
            return $this->redirectToRoute('app_home');     // Rediriger vers la page d'accueil
        }

        //////////////////////////////////////////////////////////////////////////

        return $this->render('inscription_stage/new.html.twig', [      // Pour faire le lien entre le controller et la vue new.html.twig (il faut donc la créer dans le dossier stagiaire)
            'formAddInscriptionStage' => $form,
           
        ]);
    }
}

