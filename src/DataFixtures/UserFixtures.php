<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername('usuario')
            ->setPassword('$2y$13$Q0a04iXUeRtj3Vp276dmt./I843veY8AcYRLSXfjW0wpsPVYogCzi');
        
        $manager->persist($user);
        $manager->flush();
    }
}
