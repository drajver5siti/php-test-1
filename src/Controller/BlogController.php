<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Repository\BlogPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use ApiPlatform\Core\Annotation\ApiResource;

#[Route('/blog')]
class BlogController extends AbstractController
{


    #[Route('/page', name: 'blog_list', requirements: ["page" => "\d+"])]
    public function list($page = 1, Request $request, BlogPostRepository $repository)
    {
        $items = $repository->findAll();
        $limit = $request->get('limit', 10);
        return $this->json(
            [
                "page" => $page,
                'limit' => $limit,
                'data' => array_map(function (BlogPost $item) {
                    return $this->generateUrl('blog_by_id', ['id' => $item->getSlug()]);
                }, $items),
            ]
        );
    }

    #[Route("/post/{id}", name: 'blog_by_id', requirements: ["id" => "\d+"], methods: ["GET"])]
    public function post($id, BlogPostRepository $repository)
    {
        $item = $repository->find($id);
        return $this->json(
            $item
        );
    }

    #[Route('post/{slug}', name: "blog_by_slug", methods: ["GET"])]
    public function postBySlug($slug, BlogPostRepository $repository)
    {
        $item = $repository->findOneBy(["slug" => $slug]);

        return $this->json(
            $item
        );
    }

    #[Route('/add', name: 'add_post', methods: ['POST'])]
    public function add(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }


    #[Route('/post/{id}', name: 'blog_delete', methods: ['DELETE'])]
    public function delete(BlogPost $post, EntityManagerInterface $em)
    {
        $em->remove($post);
        $em->flush();

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}
