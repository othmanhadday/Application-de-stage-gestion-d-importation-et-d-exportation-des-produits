<?php

namespace App\Controller;

use App\Entity\ArticleFicheArrivageAchat;
use App\Entity\Commantaire;
use App\Entity\Notification;
use App\Repository\ArticleRepository;
use App\Repository\FicheArrivageAchatRepository;
use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

class CommentaireController extends AbstractController
{
    private $repoUser;
    private $repoNoti;
    private $repoArticle;
    private $repoFicheArrivage;
    private $em;

    public function __construct(UserRepository $repoUser, ArticleRepository $repoArticle, EntityManagerInterface $em,
                                FicheArrivageAchatRepository $repoFicheArrivage,
                                NotificationRepository $repoNoti
    )
    {
        $this->em = $em;
        $this->repoUser = $repoUser;
        $this->repoNoti = $repoNoti;
        $this->repoArticle = $repoArticle;
        $this->repoFicheArrivage = $repoFicheArrivage;
    }

    /**
     * @Route("/api/addCommentaire", name="app_add_commentaire", methods={"POST"})
     */
    public function addArticle(UserInterface $user, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);
        $article = $this->repoArticle->find($request->get('article'));

        $commentaire = new Commantaire();
        $commentaire->setArticle($article);
        $commentaire->setUser($user);
        $commentaire->setCommentaire($request->get('commentaire'));
        $commentaire->setCreatedAt(new \DateTime());
        $commentaire->setUpdatedAt(new \DateTime());
        $this->em->persist($commentaire);
        $this->em->flush();

        $roles = ["Service Achat niveau 2", "Service Achat niveau 1", "Service Achat niveau 0", "Service Transitaire niveau 0", "Service Informatique niveau 1", "Service Informatique niveau 0"];
        foreach ($roles as $role) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setLink('/articles/' . $article->getId());
            $notification->setName($user->getFullName() . " à Ajouter un Commentaire dans  l'Article : '" . $article->getName() . "'");
            $notification->setShowIn($role);
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();
        }

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json($commentaire);
    }


    /**
     * @Route("/api/addCommentaireFicheArrivage", name="app_add_commentaireFicheArrivage", methods={"POST"})
     */
    public function addCommentaireInFicheArrivage(UserInterface $user, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);
        $ficheArrivage = $this->repoFicheArrivage->find($request->get('ficheArrivageAchat'));

        $commentaire = new Commantaire();
        $commentaire->setFicheArrivageAchat($ficheArrivage);
        $commentaire->setUser($user);
        $commentaire->setCommentaire($request->get('commentaire'));
        $commentaire->setCreatedAt(new \DateTime());
        $commentaire->setUpdatedAt(new \DateTime());
        $this->em->persist($commentaire);
        $this->em->flush();

        $roles = ["Service Achat niveau 2", "Service Achat niveau 1", "Service Achat niveau 0", "Service Transitaire niveau 0", "Service Informatique niveau 1", "Service Informatique niveau 0"];
        foreach ($roles as $role) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setLink('/fiche-arrivage/' . $ficheArrivage->getId());
            $notification->setName($user->getFullName() . " à Ajouter un Commentaire dans  Fiche Arrivage N° : '" . $ficheArrivage->getId() . "'");
            $notification->setShowIn($role);
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();
        }


        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json($commentaire);
    }


    /**
     * @Route("/api/notification", name="app_get_Notification", methods={"GET"})
     */
    public function getNotification(UserInterface $user)
    {
        $noti = $this->repoNoti->findAll();

        foreach ($noti as $i => $n) {
            if ($noti[$i]->getSeen() === true) {
                unset($noti[$i]);
            }
        }

        foreach ($noti as $i => $n) {
            if ($noti[$i]->getUser() === $user) {
                unset($noti[$i]);
            }
        }

        foreach ($noti as $index => $n) {
            if (strpos($noti[$index]->getShowIn(), $user->getRole()->getService()->getName() . ' ' . $user->getRole()->getNiveau()->getName()) === false && $noti[$index]->getShowIn() != $user->getUserId()) {
                unset($noti[$index]);
            }
        }
        return $this->json(array_values($noti));
    }

    /**
     * @Route("/api/Allnotification", name="app_get_allNotification", methods={"GET"})
     */
    public function getAllNotification(UserInterface $user)
    {
        $noti = $this->repoNoti->findAll();

        foreach ($noti as $i => $n) {

            if ($noti[$i]->getUser() === $user) {
                unset($noti[$i]);
            }
        }

        foreach ($noti as $index => $n) {

            if (strpos($noti[$index]->getShowIn(), $user->getRole()->getService()->getName() . ' ' . $user->getRole()->getNiveau()->getName()) === false && $noti[$index]->getShowIn() != $user->getUserId()) {
                unset($noti[$index]);
            }
        }

        return $this->json(array_values($noti));
    }


    /**
     * @Route("/api/sendNotification", name="app_send_notification", methods={"POST"})
     */
    public function sendNotification(UserInterface $user, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $roles = $request->get('roles');
        foreach ($roles as $role) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setLink($request->get('link'));
            $notification->setName($request->get('name'));
            $notification->setShowIn($role);
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();
        }


        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => "Notificaton sended"]));
        $publisher($update);

        return $this->json("Notificaton sended");
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
