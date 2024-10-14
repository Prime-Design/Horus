<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Rubrik;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\PostRepository;
use App\Repository\RubrikRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gedmo\Mapping\Annotation\Slug;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    private $repo;
    private $emi;

    public function __construct(PostRepository $repo, EntityManagerInterface $emi)
    {
        $this->repo = $repo;
        $this->emi = $emi;
    }

    #[Route('/', name: 'app_post')]
    public function index(): Response
    {
        $posts = $this->repo->findBy([], ['createdAt' => 'DESC'], 3);

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/post/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function showone(Post $post, Request $req, CommentRepository $crepo): Response
    {
        if (!$post) {
            return $this->redirectToRoute('app_post');
        }

        $comments = new Comment();
        $commentForm = $this->createForm(CommentFormType::class, $comments);
        $commentForm->handleRequest($req);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $user = $this->getUser();
            $comments->setUser($user);
            $comments->setPost($post);
            $comments->setCreatedAt(new \DateTimeImmutable());

            $this->emi->persist($comments);
            $this->emi->flush();

            return $this->redirectToRoute('show', ['id' => $post->getId()]);
        }

        $allComments = $crepo->findByPostOrderedByCreatedAtDesc($post->getId());

        return $this->render('show/show.html.twig', [
            'post' => $post,
            'comments' => $allComments,
            'comment_form' => $commentForm->createView(),
        ]);
    }


    #[Route('/rubrik/rubrik/{id}', name: 'posts_by_rubrik')]
    public function postsByRubrik(Rubrik $rubrik, PostRepository $postRepository, RubrikRepository $rubrikRepository): Response
    {


        $rubrik = $rubrikRepository->find($rubrik->getId());
        if (!$rubrik) {
            throw $this->createNotFoundException('Rubrik non trouvée');
        }
        $posts = $postRepository->findByRubrik($rubrik);



        return $this->render('rubrik/rubrik.html.twig', [
            'rubrik' => $rubrik,
            'posts' => $posts
        ]);
    }
}
