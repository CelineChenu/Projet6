<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $product = new User();
        $product->setEmail('test@gmail.com');
        $product->setPassword('test');
        $manager->persist($product);


        $manager->flush();
    }
}
