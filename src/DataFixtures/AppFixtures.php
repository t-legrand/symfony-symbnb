<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // Gestion de l'utilisateur admin
        $faker = Factory::create('fr_FR');

        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        $adminUser = new User();
        $adminUser->setFirstName('Thomas')
                ->setLastName('Legrand')
                ->setEmail('legrandt84@gmail.com')
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>')
                ->setHash($this->encoder->encodePassword($adminUser, 'azer'))
                ->setPicture('https://randomuser.me/api/portraits/lego/6.jpg')
                ->addUserRole($adminRole);

                $manager->persist($adminUser);
                

        // Gestion des utilisateurs
        $users = [];
        $genres = ['male', 'female'];

        for($i = 1; $i <= 10; $i++)
        {
            $user = new User();

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(0,99) . '.jpg';

            $picture .= ($genre == 'male' ? 'men/' : 'women/') . $pictureId;
            
            $hash = $this->encoder->encodePassword($user,'password');


            $user->setFirstName($faker->firstName())
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(2)) . '</p>')
                ->setHash($hash)
                ->setPicture($picture);

                $manager->persist($user);
                $users[] = $user;
        }

        // Gestion des annonces
        for($i = 1; $i <= 30; $i++)
        {
            $ad = new Ad;

            $title = $faker->sentence(5,true);
            $coverImage = "https://loremflickr.com/1000/350/house";
            $introduction = $faker->paragraph(2);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(5)) . '</p>';

            $user = $users[mt_rand(0,count($users) - 1)];


            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent("$content")
                ->setPrice(mt_rand(40,200))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user);

            for($j = 1; $j<mt_rand(2,5); $j++)
            {
                $image = new Image();

                $image->setUrl("https://loremflickr.com/640/48" . $j . "/room")
                    ->setCaption($faker->sentence())
                    ->setAd($ad);

                $manager->persist($image);
            }

            // Gestion des réservations
            for($j = 1; $j <= mt_rand(0, 10); $j++){
                $booking = new Booking();

                $createdAt = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                // Gestion de la date de fin
                $duration = mt_rand(3, 10);

                $endDate = (clone $startDate)->modify("+$duration days");
                $amount = $ad->getPrice() * $duration;
                
                $booker =  $users[mt_rand(0, count($users) -1)];

                $comment = $faker->paragraph();

                $booking->setBooker($booker)
                        ->setAd($ad)
                        ->setStartDate($startDate)
                        ->setEndDate($endDate)
                        ->setCreatedAt($createdAt)
                        ->setAmount($amount)
                        ->setComment($comment);
                
                $manager->persist($booking);
            }

            $manager->persist($ad);
        }
        $manager->flush();
    }
}
