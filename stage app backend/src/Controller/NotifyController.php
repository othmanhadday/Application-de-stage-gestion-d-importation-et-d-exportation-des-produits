<?php

namespace App\Controller;

use App\Entity\AlertFcheArrivage;
use App\Entity\FicheArrivageAchat;
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

class NotifyController extends AbstractController
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
     * @Route("/api/sendAlert", name="app_send_alert", methods={"POST"})
     */
    public function sendAlert(Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $ficheArrivage = $this->repoFicheArrivage->find($request->get('ficheArrivageId'));

        $role = $request->get('role');
        $notification = new AlertFcheArrivage();
        $notification->setName($request->get('name'));
        $notification->setFicheArrivageAchat($ficheArrivage);
        $notification->setShowIn($role);
        $this->em->persist($notification);
        $this->em->flush();


        $update = new Update("http://localhost:8000/alert",
            json_encode(['message' => "Notificaton sended"]));
        $publisher($update);

        return $this->json("Notificaton sended");
    }


    /**
     * @Route("/api/updateFicheArrivageRealTime/{id<[0-9]+>}", name="app_send_relTime", methods={"POST"})
     */
    public function notificationRealTime(FicheArrivageAchat $ficheArrivageAchat, PublisherInterface $publisher)
    {

        $update = new Update("http://localhost:8000/updateFicheArrivage",
            json_encode(['message' => $ficheArrivageAchat->getId()]));
        $publisher($update);

        return $this->json($ficheArrivageAchat);
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
