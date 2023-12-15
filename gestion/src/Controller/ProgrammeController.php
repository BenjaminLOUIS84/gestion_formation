<?php

namespace App\Controller;

use App\Repository\ProgrammeRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProgrammeController extends AbstractController                                // AFFICHER LA LISTE DES PROGRAMMES
{
    #[Route('/programme', name: 'app_programme')]                                   // Route représentant l'URL '/programme' pour la redirection et le name: sert pour la navigation

    public function index(ProgrammeRepository $programmeRepository): Response       // Pour afficher la liste des programmes insérer dans la fonction index() ProgrammeRepository $programmeRepository
    {                                                                               // Importer la classe ProgrammeRepository avec un click droit
        $programmes = $programmeRepository->findAll();                              // Pour récupérer la liste des programmes classés par défaut
        
        return $this->render('programme/index.html.twig', [                         // render() Permet de faire le lien entre le controller et la view 
            
            'programmes' => $programmes                                             // Pour passer la variable $programmes en argument 'programmes'
        ]);                                                                         // Pour afficher cet argument dans la vue il faut créer un echo représenté par {{ }}
    }                                                                               // Dans le fichier index.html.twig du dossier programme
}
