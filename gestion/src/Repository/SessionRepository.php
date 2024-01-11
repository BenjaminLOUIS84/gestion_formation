<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Session>
 *
 * @method Session|null find($id, $lockMode = null, $lockVersion = null)
 * @method Session|null findOneBy(array $criteria, array $orderBy = null)
 * @method Session[]    findAll()
 * @method Session[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //REQUETES SQL -> DQL
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //Pour afficher les sessions passée, en cours et à venir dans la liste des sessions

        // 1 Créer une requête SQL dans la BDD pour matérialiser le mécanisme
        // 2 Adapter cette requête en DQL
        // 3 Créer les fonctions showPast(), showPresent(), showFutur() dans le SessionController.php
        // 4 Créer les chemins d'accès href="{{path ('futur_session')}}">Sessions à venir dans la vue index twig

        // Ci dessous sont des requêtes DQL

        public function past($date_fin): ?array
        { 
            /////////////////////DQL//////////////////////                   PASSEE                     /////////////////////// SQL////////////////////

            return $this                                                                                // $entityManager = $this->getEntityManager();
                ->createQueryBuilder('session')                                                         // $query = $entityManager->createQuery(                    
                ->andWhere('session.date_fin < CURRENT_DATE()')                                         // WHERE s.date_fin < CURDATE()       

                //->setParameter('date_fin', $date_fin)   Utile pour les formulaires                    // ->setParameter('date_fin', $date_fin);
                ->getQuery()                                                                            // return $query->getResult();
                ->getResult()
            ;

        }

        public function present($date_fin): ?array
        { 
            /////////////////////DQL//////////////////////                     PRESENT                  /////////////////////// SQL////////////////////

            return $this                                                                                // $entityManager = $this->getEntityManager();
                ->createQueryBuilder('session')                                                         // $query = $entityManager->createQuery(                    
                ->andWhere('session.date_debut < CURRENT_DATE() AND session.date_fin > CURRENT_DATE()')    // WHERE s.date_debut < CURDATE() AND s.date_fin < CURDATE()       
                ->getQuery()                                                                            // return $query->getResult();
                ->getResult()
            ;

        }

        public function futur($date_debut): ?array
        { 
            /////////////////////DQL//////////////////////                     FUTUR                    /////////////////////// SQL////////////////////

            return $this                                                                                // $entityManager = $this->getEntityManager();
                ->createQueryBuilder('session')                                                         // $query = $entityManager->createQuery(                    
                ->andWhere('session.date_debut > CURRENT_DATE() AND session.date_fin > CURRENT_DATE()') // WHERE s.date_debut > CURDATE() AND session.date_fin > CURRENT_DATE()   
                ->getQuery()                                                                            // return $query->getResult();
                ->getResult()
            ;

        }

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Créer une fonction pour afficher la liste des stagiaires non inscrit dans une session
        // Créer la requête DQL selectionner tous les stagiaires inscrit dans une session
        // Créer une requête NOT IN tous les stagiaires qui ne sont pas dans la requête précédente 

        public function findStagiairesNonInscrit($sessionId)
        {
            $em = $this->getEntityManager();             // On demande au repository d'accéder au manager d'entités je veux avoir la permission de manipuler des données
           
            $requete = $em->createQueryBuilder();        // Pour accéder à la méthode createQueryBuilder () créer un constructeur pour manipuler des objects
           
            $qB = $requete;                               
        
            $qB
                ->select('st')                            // Sélectionner tous les stagiaires dont l'id est passé en paramêtre ('s') équivaut à table from
                ->from('App\Entity\stagiaire', 'st')     // Séléctionne moi toute les colones de stagiaire d'une session 
                ->leftjoin('st.sessions', 'session')
                ->where('session.id = :id')
            ;

            $requete = $em->createQueryBuilder();       // Trouver les stagiaires non inscrits
            
            $requete

                ->select('stagiaire')               
                ->from('App\Entity\stagiaire', 'stagiaire') 

                ->where($requete->expr()->NotIn('stagiaire.id', $qB->getDQL()))   // Les résultats de cette requête n'est pas dans le résultat de la requête qB                        //->where($sub->expr()->NotIn('st.id', $qb->getDQL()))
                
                ->setParameter(':id',$sessionId); 

                $query = $requete->getQuery();          // Renvoyer le résultat
                return $query->getResult()
            ;
        }                                               

        //////////////////////////////////////////////////////////////////////////////////////////////////////

        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // Créer une fonction pour afficher la liste des modules non programmé dans un programme
        // Créer la requête DQL selectionner tous les modules programmé dans un programme
        // Créer une requête NOT IN tous les modules qui ne sont pas dans la requête précédente 

        public function findMatiereNonProgramme($sessionId)
        {
            $em = $this->getEntityManager();             // On demande au repository d'accéder au manager d'entités je veux avoir la permission de manipuler des données
            $requete = $em->createQueryBuilder();        // Pour accéder à la méthode createQueryBuilder () créer un constructeur pour manipuler des objects
            $qB = $requete;                               
        
            $qB
                ->select('module')                            // Sélectionner tous les modules dont l'id est passé en paramêtre ('s') équivaut à table from
                ->from('App\Entity\matiere', 'module')        // Séléctionne moi toute les colones de stagiaire d'une session 
                ->leftjoin('module.programmes', 'p')
                ->where('p.session = :id')              // Condition où l'Id de la session dans programme correspond à :id
            ;

            $requete = $em->createQueryBuilder();       // Trouver les modules non inscrits
            $requete
                ->select('matiere')               
                ->from('App\Entity\matiere', 'matiere') 
                ->where($requete->expr()->NotIn('matiere.id', $qB->getDQL()))   // Les résultats de cette requête n'est pas dans le résultat de la requête qB 
                ->setParameter(':id',$sessionId); 
               
                $query = $requete->getQuery();          // Renvoyer le résultat
                return $query->getResult()
            ;
        }                                               

        //////////////////////////////////////////////////////////////////////////////////////////////////////


    //    /** EXEMPLES DE REQUETES DQL

    //     * @return Session[] Returns an array of Session objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val') équivaut à WHERE
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10) équivaut à limit
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //     public function findAllGreaterThanPrice(int $price): array
    //     {
    //         $entityManager = $this->getEntityManager();

    //         $query = $entityManager->createQuery(
    //             'SELECT p
    //             FROM App\Entity\Product p
    //             WHERE p.price > :price
    //             ORDER BY p.price ASC'
    //         )->setParameter('price', $price);

    //         // returns an array of Product objects
    //         return $query->getResult()

    //         ;
    //     }
    // 
    //    public function findOneBySomeField($value): ?Session
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
