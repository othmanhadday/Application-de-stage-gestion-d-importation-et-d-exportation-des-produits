<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Notification;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use App\Repository\CommantaireRepository;
use App\Repository\ModelRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $repoCat;
    private $repoArticle;
    private $repoModels;
    private $repoCommentaire;
    private $repoUser;
    private $em;
    private $repoNoti;

    public function __construct(EntityManagerInterface $em, CategorieRepository $repoCat,
                                UserRepository $repoUser, NotificationRepository $repoNoti
        , ArticleRepository $repoArticle, ModelRepository $repoModels, CommantaireRepository $repoCommentaire)
    {
        $this->em = $em;
        $this->repoCat = $repoCat;
        $this->repoUser = $repoUser;
        $this->repoNoti = $repoNoti;
        $this->repoArticle = $repoArticle;
        $this->repoModels = $repoModels;
        $this->repoCommentaire = $repoCommentaire;
    }

    /**
     * @Route("/api/addArticle", name="app_add_article", methods={"POST"})
     */
    public function addArticle(Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $categorie = $this->repoCat->find($request->get('categorie')['id']);
        $user = $this->repoUser->find($request->get('userId'));


        $article = new Article();
        $article->setName($request->get('name'));
        $article->setQuantiteTotal($request->get('quantiteTotal'));
        $article->setRefArticle($request->get('refArticle'));
        $article->setCategorie($categorie);
        $this->em->persist($article);
        $this->em->flush();


        $roles = ["Service Achat niveau 1", "Service Achat niveau 0", "Service Transitaire niveau 0", "Service Informatique niveau 1", "Service Informatique niveau 0"];
        foreach ($roles as $role) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setLink('/articles/' . $article->getId());
            $notification->setName($user->getFullName() . " à Ajouter un nouveau Article : '" . $article->getName() . "' tu dois vérifier cet article !!");
            $notification->setShowIn($role);
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();
        }


        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);

        return $this->json($article->jsonSerialize());
    }


    /**
     * @Route("/api/searchArticle/{searchValue}", name="app_Search_articles", methods={"GET"})
     */
    public function searchArticle(Request $request, $searchValue)
    {
        $articles = $this->repoArticle->findByrefArtAndName($searchValue);
        return $this->json($articles);
    }


    /**
     * @Route("/api/articlesId/{id<[0-9]+>}", name="app_getById_articles", methods={"GET"})
     */
    public function getArticleById(Article $article, Request $request)
    {
        $request = $this->transformJsonBody($request);

        $models = $this->repoModels->findBy(['article' => $article]);
        $commentaires = $this->repoCommentaire->findBy(['article' => $article]);

        return $this->json([
            'article' => $article,
            'models' => $models,
            'commentaires' => $commentaires
        ]);
    }


    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            return $request;
        }
        $request->request->replace($data);
        return $request;
    }
}
