<?php

namespace Sadio\JobsPlateformBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * OfferRepository
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OfferRepository extends \Doctrine\ORM\EntityRepository
{
    // Permet de récupérer une Offre avec ses catégories => quand on fait offer.getCategories, 
    // Pas de requetes SQL supplémentaire déclenchée
    public function findOneWithAllRelations($offerSlug) 
    {
        // "o" represente l'alias de Offer, "c" celui de Category et "u" celui de User
        $qb = $this->createQueryBuilder('o');

        // On ajoute d'abord Category
        $qb->leftJoin('o.categories', 'c')
           ->addSelect('c')

        // On ajoute ensuite User
           ->leftJoin('o.user', 'u')
           ->addSelect('u')

        // Enfin on met la condition
           ->where('o.slug = :slug')
           ->setParameter('slug', $offerSlug);
        
        // Ici nous utilisons un Bloc Try/Catch pour $qb->getQuery()->getSingleResult()
        // En effet getSingleResult() déclenche automatiquement une erreur s'il ne trouve rien => catch permet
        // d'attraper cette Exception afin que le script continue
        try {
            return $qb->getQuery()->getSingleResult();
        } 
        catch(\Exception $e) { }
        // return $qb->getQuery()->setMaxResults(1)->getOneOrNullResult(); meme chose que la méthode en haut, 
        // renvoie un objet. Mais si la méthode ne trouve rien => aucune erreur n'est déclenchée => pas besoin du 
        // bloc try/catch. Cependant, Vu que le Max result est 1, s'il existe plusieurs catégories dans la jointure
        // Seul le premier résultat sera sélectionné.
    }
    // Permet de récupérer une Offre avec ses catégories => quand on fait offer.getCategories dans TWIG, 
    // Pas de requetes SQL supplémentaire déclenchée
    public function findAllWithUser($page, $offersPerPage) 
    {
        // "o" represente l'alias de Offer et "u" celui de User
        $qb = $this->createQueryBuilder('o');

        $qb->innerJoin('o.user', 'u')
           ->addSelect('u')
           ->orderBy('o.id', 'DESC');
        
        // On définit l'annonce à partir de laquelle commencer la liste
        // Ainsi que le nombre d'annonce à afficher sur une page
        $qb->setFirstResult(($page-1) * $offersPerPage)
           ->setMaxResults($offersPerPage);

        // Enfin, on retourne l'objet Paginator correspondant à la requête construite
        // (n'oubliez pas le use correspondant en début de fichier)
        return new Paginator($qb, true);
    }
}
