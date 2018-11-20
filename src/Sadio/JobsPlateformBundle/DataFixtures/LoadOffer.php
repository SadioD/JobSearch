<?php

namespace Sadio\JobsPlateformBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sadio\JobsPlateformBundle\Entity\Offer;
use Sadio\JobsPlateformBundle\Entity\Category;
use Sadio\AuthBundle\Entity\User;

class LoadOffer extends Fixture 
{
    public function load(ObjectManager $manager) 
    {
        // Lets create a list of offers and categories to be persisted
        /*$list = [
            [
                'position'      => 'Expert MVC',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.'
            ],
            [
                'position'      => 'Php Unit Testeur',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.'
            ],
            [
                'position'      => 'Développeur Codeigniter',
                'description'   => 'Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace. Few wodivd argue that, despite the advancements of feminism over the past three decades, women still face a double standard when it comes to their behavior. While men’s borderline-inappropriate behavior is often laughed off as “boys will be boys,” women face higher conduct standards – especially in the workplace.'
            ]
        ];
        $firstCategory = new Category(['name' => 'Développeur Full-Stack']);
        $secndCategory = new Category(['name' => 'Développeur Back-End']);
            
        // Lets link these categories to Products and persist them all
        for ($i=0; $i < count($list); $i++) 
        {
            $offer = new Offer($list[$i]);
            $offer->addCategory($firstCategory);
            $offer->addCategory($secndCategory);

            // We don't have to create a shortDesc 
            // Since Offer method createShortDesc() creates it anytime we persist (creation or update)
           
            // On lie toutes ces annones au User dont id = 4
            $offer->setUser($manager->find(User::class, 4));

            // We only persist $offer because of cascade={"persist"} clause
            $manager->persist($offer);
        }
        $manager->flush();*/
        /*$offer = $manager->find(Offer::class, 3);
        $offer->setPosition('Développeuse Symfony 4');
        $manager->flush();*/
    }
}