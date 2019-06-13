<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\BlogPost;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Comment;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface $encoder
     */
    private $encoder;

    /**
     * @var \Faker\Factory
     */
    private $faker;
    
    public const ADMIN_USER_REFERENCE = 'admin-user';
    public const BLOG_USER_REFERENCE = 'blog_post_$i';
    
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Faker\Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);    
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
        
        
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
    
        //$user = $this->getReference(self::ADMIN_USER_REFERENCE);

        for($i = 0; $i < 100; $i++){
            $blogPost = new BlogPost();

            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTimeThisYear);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($this->getReference(self::ADMIN_USER_REFERENCE));
            $blogPost->setSlug($this->faker->slug);

            $this->setReference(self::BLOG_USER_REFERENCE, $blogPost);

            $manager->persist($blogPost);

        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for($i = 0 ; $i < 100; $i++){
            for($j = 0; $j < rand(1,10); $j++)
            {
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference(self::ADMIN_USER_REFERENCE));
                $comment->setBlogPosts($this->getReference(self::BLOG_USER_REFERENCE));

                $manager->persist($comment);
            }
            $manager->flush();
        }
    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@blog.com');
        $user->setFullname('admin sys');
        $user->setPassword($this->encoder->encodePassword($user, 'admin123'));

        $this->addReference(self::ADMIN_USER_REFERENCE, $user);


        $manager->persist($user);

        $manager->flush();
    }
}
