<?php

namespace App\Controller\Backend;

use index;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/articles', name: 'admin.articles')]
class ArticleController extends AbstractController
{
    public function __construct(
        private ArticleRepository $articleRepo,
    ) {
    }

    #[Route('', name: '.index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('Backend/Article/index.html.twig', [
            'articles' => $this->articleRepo->findAll(),
        ]);
    }

    #[Route('/create', name: '.create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $article = new Article();

        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Associer l'utilisateur connecté à l'article
            $article->getUsers($user);

            $this->articleRepo->save($article);

            $this->addFlash('success', 'Article créé avec succès');

            return $this->redirectToRoute('admin.articles.index');
        }

        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/create', name: 'admin.article.create')]
    public function createArticle(Request $request, ArticleRepository $repoArticle)
    // la méthode createArticle() est appelée par la route admin.article.create
    {
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repoArticle->save($article, true);
        }

        return $this->render('Backend/Article/create.html.twig', [
            'form' => $form,
        ]);
    }
}
