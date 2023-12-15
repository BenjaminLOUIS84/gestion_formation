<?php

namespace App\Controller;

use App\Repository\FormationRepository;                                         // La classe importée s'ajoute ici automatiquement
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
}
