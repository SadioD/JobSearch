<?php

namespace Sadio\AuthBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Sadio\AuthBundle\Entity\User;

class LoadUser extends Fixture 
{
    public function load(ObjectManager $manager) 
    {
        // Lets create a list of users to be persisted
        /*$users = [
            [
                'name'           => 'Banque Mondiale',
                'email'          => 'aaa@yahoo.dr',
                'password'       => 'abcdef',
                'numberOfOffers' => 1
            ],
            [
                'name'           => 'Le Crédit Lyonnais',
                'email'          => 'agugga@yahoo.dr',
                'password'       => 'cdef',
                'numberOfOffers' => 1
            ],
            [
                'name'           => 'La Poste Mobile',
                'email'          => 'ayutygg@yahoo.dr',
                'password'       => 'abc',
                'numberOfOffers' => 1
            ]
        ];
            
        // Lets link these categories to Products and persist them all
        for ($i=0; $i < count($users); $i++) 
        {
            $user = new User($users[$i]);
            $manager->persist($user);
        }
        $manager->flush();*/
    }
}