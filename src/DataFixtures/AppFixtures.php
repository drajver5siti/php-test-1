<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $blogPost = new BlogPost();
        $blogPost->setTitle('Fixture first post');
        $blogPost->setPublished(new \DateTime('2020-08-01 12:00:00'));
        $blogPost->setContent('Fixture content');
        $blogPost->setAuthor("Ivan");
        $blogPost->setSlug("fixture slug");
        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle('Fixture second post');
        $blogPost->setPublished(new \DateTime('2020-08-01 12:00:00'));
        $blogPost->setContent('Fixture content 2');
        $blogPost->setAuthor("Ivan");
        $blogPost->setSlug("fixture slug 2");
        $manager->persist($blogPost);

        $manager->flush();
    }
}
