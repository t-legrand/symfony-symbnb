<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use App\Entity\Image;
use Faker\Factory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 30; $i++)
        {
            $ad = new Ad;

            $title = $faker->sentence(5,true);
            $coverImage = "https://loremflickr.com/1000/350/house";
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';


            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent("$content")
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5));

            for($j = 1; $j<mt_rand(2,5); $j++)
            {
                $image = new Image();

                $image->setUrl("https://loremflickr.com/640/48" . $j . "/room")
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
