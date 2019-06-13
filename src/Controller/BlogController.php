<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
      
        
    /**
     * @Route("/{page}", name="blog_list", defaults={"page": 5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
        $limit = $request->get('limit', 10);

        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

        return $this->json(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(function(BlogPost $item){
                    return $this->generateUrl('blog_by_slug', ['slug' => $item->getSlug()]);
                }, $items)    
            ]           
        );
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"}, methods={"GET"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post($post)
    {
        return $this->json(
            $post
        );
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug",  methods={"GET","HEAD"})
     * @ParamConverter("post", options={"mapping": {"slug": "slug"}}, class="App:BlogPost")
     */
    public function postBySlug($post)
    {
        return $this->json(
           $post
        );
    }


    /**
     * @Route("/post/{id}", name="blog_delete",  methods={"DELETE"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function delete($post)
    {

        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        return $this->json(
           null, Response::HTTP_NO_CONTENT
        );
    }

     /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
        /** Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();

        $em->persist($blogPost);

        //dump($blogPost);die;
        dump('salut');
        $em->flush();

        return $this->json($blogPost);
    }


}
