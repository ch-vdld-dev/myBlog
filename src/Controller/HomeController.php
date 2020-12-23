<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/change_locale/{locale}", name="change_locale")
     */
    public function changeLocale($locale, Request $request)
    {
        $request->getSession()->set('_locale', $locale);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(PostRepository $repo)
    {
        $posts = $repo->findAll();

        return $this->render('home/index.html.twig', [
            "posts" => $posts
        ]);
    }

    /**
     * @Route("/posts/{slug}", name="show_post")
     */
    public function show(post $post, Request $request, EntityManagerInterface $em, TranslatorInterface $translator)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em->persist($comment);
            $em->flush();

            $message = $translator->trans("Comment saved");
            $this->addFlash("message", $message);
        }

        return $this->render('home/post.html.twig', [
            "post" => $post,
            "form" => $form->createView()
        ]);
    }

}
