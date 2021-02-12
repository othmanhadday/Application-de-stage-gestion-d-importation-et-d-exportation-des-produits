<?php

namespace App\Controller;

use App\Entity\ArticleFicheArrivageAchat;
use App\Entity\FicheArrivageFinance;
use App\Entity\FicheArrivageMagasinier;
use App\Entity\Notification;
use App\Repository\ArticleFicheArrivageAchatRepository;
use App\Repository\CommantaireRepository;
use App\Repository\ConteneurRepository;
use App\Repository\DepotRepository;
use App\Repository\FicheArrivageAchatRepository;
use App\Repository\FicheArrivageMagasinierRepository;
use App\Repository\FicheArrivageTransitaireRepository;
use App\Repository\ImageRepository;
use App\Repository\ModelRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;


class FicheArrivageFinanceController extends AbstractController
{
    private $em;
    private $repoFicheArrivageFinance;
    private $repoUser;
    private $repoArticleFicheArrivag;

    public function __construct(EntityManagerInterface $em,
                                UserRepository $repoUser,
                                ArticleFicheArrivageAchatRepository $repoArticleFicheArrivag,
                                FicheArrivageAchatRepository $repoFicheArrivageFinance)
    {
        header("Access-Control-Allow-Origin: *");
        $this->em = $em;
        $this->repoFicheArrivageFinance = $repoFicheArrivageFinance;
        $this->repoUser = $repoUser;
        $this->repoArticleFicheArrivag = $repoArticleFicheArrivag;
    }

    /**
     * @Route("/api/verifyCleExterne", name="app_verify_cleExterne", methods={"POST"})
     */
    public function verifyCleExterne(Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $user = $this->repoUser->find($request->get('user'));
        $articleFicheArrivage = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));

        $ficheArrivageFnance = new FicheArrivageFinance();
        $ficheArrivageFnance->setUser($user);
        $ficheArrivageFnance->setArticleFicheArrivage($articleFicheArrivage);
        $ficheArrivageFnance->setCleExterne($request->get('cleExterne'));
        $this->em->persist($ficheArrivageFnance);
        $this->em->flush();

        if ($ficheArrivageFnance->getCleExterne() == true) {
            $notification = new Notification();
            $notification->setSeen(false);
            $notification->setUser($user);
            $notification->setLink('/fiche-arrivage-finance/' . $articleFicheArrivage->getFicheArrivageAchat()->getId());
            $notification->setName("Fiche Arrivage : " . $articleFicheArrivage->getFicheArrivageAchat()->getId() . " risque d'ecart Service Achat");
            $notification->setShowIn("Service Achat niveau 0");
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();

            $update = new Update("http://localhost:8000/notification",
                json_encode(['message' => $notification]));
            $publisher($update);
        }

        return $this->json($ficheArrivageFnance);
    }


    /**
     * @Route("/api/verifyCleDouan", name="app_verify_cleDouan", methods={"POST"})
     */
    public function verifyCleDouan(Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $user = $this->repoUser->find($request->get('user'));
        $articleFicheArrivage = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));

        $ficheArrivageFnance = new FicheArrivageFinance();
        $ficheArrivageFnance->setUser($user);
        $ficheArrivageFnance->setArticleFicheArrivage($articleFicheArrivage);
        $ficheArrivageFnance->setCleDouan($request->get('cleDouan'));
        $this->em->persist($ficheArrivageFnance);
        $this->em->flush();

        if ($ficheArrivageFnance->getCleDouan() == true) {
            $roles=['Service Ressource humaine','Service Administration'];
            foreach ($roles as $role){
                $notification = new Notification();
                $notification->setSeen(false);
                $notification->setUser($user);
                $notification->setLink('/fiche-arrivage-finance/' . $articleFicheArrivage->getFicheArrivageAchat()->getId());
                $notification->setName("Fiche Arrivage : " . $articleFicheArrivage->getFicheArrivageAchat()->getId() . " risque d'ecart transport exterieure");
                $notification->setShowIn($role);
                $notification->setCreatedAt(new \DateTime());
                $notification->setUpdatedAt(new \DateTime());
                $this->em->persist($notification);
                $this->em->flush();
            }


            $update = new Update("http://localhost:8000/notification",
                json_encode(['message' => $notification]));
            $publisher($update);
        }

        return $this->json($ficheArrivageFnance);
    }


    /**
     * @Route("/api/verifyTemps/{id<[0-9]+>}", name="app_verify_temps", methods={"POST"})
     */
    public function verifyTempsSup3h(FicheArrivageFinance $ficheArrivageFinance, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $user = $this->repoUser->find($request->get('user'));
        $articleFicheArrivage = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));

        $ficheArrivageFinance->setVerifierTemps($request->get('temps'));
        $this->em->persist($ficheArrivageFinance);
        $this->em->flush();

        if ($ficheArrivageFinance->getVerifierTemps() == true) {
            $notification = new Notification();
            $notification->setSeen(false);
            $notification->setUser($user);
            $notification->setLink('/fiche-arrivage-finance/' . $articleFicheArrivage->getFicheArrivageAchat()->getId());
            $notification->setName("Fiche Arrivage : " . $articleFicheArrivage->getFicheArrivageAchat()->getId() . " risque d'ecart Service Transitaire");
            $notification->setShowIn("Service Transitaire niveau 0");
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();

            $update = new Update("http://localhost:8000/notification",
                json_encode(['message' => $notification]));
            $publisher($update);
        }
        return $this->json($ficheArrivageFinance);
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
