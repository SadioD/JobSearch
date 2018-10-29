<?php

namespace Sadio\JobsPlateformBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Entity\Category;

class LoadOffer extends Fixture 
{
    public function load(ObjectManager $manager) 
    {
        // Lets create a list of offers and categories to be persisted
        $list = [
            [
                'position'      => 'Développeur Symfony',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'author'        => 'Banque Mondiale'
            ],
            [
                'position'      => 'Développeur Codeigniter',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'author'        => 'Le Crédit Lyonnais'
            ],
            [
                'position'      => 'Développeur Python',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.',
                'author'        => 'La Poste Mobile'
            ]
        ];
        $firstCategory = new Category(['name' => 'Développeur Full-Stack']);
        $secndCategory = new Category(['name'  => 'Développeur Back-End']);
            
        // Lets link these categories to Products and persist them all
        for ($i=0; $i < count($list); $i++) 
        {
            $offer = new Offer($list[$i]);
            $offer->addCategory($firstCategory);
            $offer->addCategory($secndCategory);

            // We then create a short description for each offer
            if (strlen($list[$i]['description']) > 500) {
                $shortDesc = substr($list[$i]['description'], 0, 500);
                $shortDesc = substr($shortDesc, 0, strrpos($shortDesc, ' ')) . '...';
            } else {
                $shortDesc = $list[$i]['description'];
            }
            $offer->setShortDesc($shortDesc);

            // We only persist $offer because of cascade={"persist"} clause
            $manager->persist($offer);
        }
        $manager->flush();
    }
}