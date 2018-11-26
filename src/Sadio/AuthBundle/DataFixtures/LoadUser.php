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
                'username'           => 'Banque Mondiale',
                'email'          => 'aaa@yahoo.dr',
                'password'       => 'abcdef',
                'numberOfOffers' => 1
            ],
            [
                'username'           => 'Le Crédit Lyonnais',
                'email'          => 'agugga@yahoo.dr',
                'password'       => 'cdef',
                'numberOfOffers' => 1
            ],
            [
                'username'           => 'La Poste Mobile',
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
        $user = new User(['username'     => 'foujithTestUser',
                          'email'    => 'ahimasaoijjd85@jiookgmail.com',
                          'password' => 'hhiuiui89oihuii']);
        
        $manager->persist($user);
        $manager->flush();
    }
}