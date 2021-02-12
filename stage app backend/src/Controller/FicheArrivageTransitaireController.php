<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Response;
use App\Entity\Dume;
use App\Entity\FicheArrivageAchat;
use App\Entity\FicheArrivageTransitaire;
use App\Entity\Image;
use App\Entity\ImageInsideConteneur;
use App\Entity\ImageOutsideConteneur;
use App\Entity\Notification;
use App\Repository\ArticleFicheArrivageAchatRepository;
use App\Repository\CommantaireRepository;
use App\Repository\ConteneurRepository;
use App\Repository\DepotRepository;
use App\Repository\FicheArrivageAchatRepository;
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
use Symfony\Component\Security\Core\User\UserInterface;

class FicheArrivageTransitaireController extends AbstractController
{
    private $em;
    private $repoFicheArrivageAchat;
    private $repoFicheArrivageTranstaire;
    private $repoConteneur;
    private $repoModel;
    private $repoUser;
    private $repoArticleFicheArrivag;
    private $repoImage;
    private $repoDepot;
    private $repoCommentaire;

    public function __construct(EntityManagerInterface $em,
                                FicheArrivageAchatRepository $repoFicheArrivage,
                                FicheArrivageTransitaireRepository $repoFicheArrivageTranstaire,
                                ConteneurRepository $repoConteneur,
                                ModelRepository $repoModel,
                                UserRepository $repoUser,
                                ArticleFicheArrivageAchatRepository $repoArticleFicheArrivag,
                                ImageRepository $repoImage,
                                DepotRepository $repoDepot,
                                CommantaireRepository $repoCommentaire
    )
    {
        $this->em = $em;
        $this->repoModel = $repoModel;
        $this->repoUser = $repoUser;
        $this->repoConteneur = $repoConteneur;
        $this->repoFicheArrivageAchat = $repoFicheArrivage;
        $this->repoFicheArrivageTranstaire = $repoFicheArrivageTranstaire;
        $this->repoArticleFicheArrivag = $repoArticleFicheArrivag;
        $this->repoImage = $repoImage;
        $this->repoDepot = $repoDepot;
        $this->repoCommentaire = $repoCommentaire;
    }


    /**
     * @Route("/api/addFicheArrivageTransitaire", name="app_add_FcheArrivageTransitaire", methods={"POST"})
     */
    public function addFicheArrivageTransitaire(Request $request, UserInterface $user, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $artiFiche = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));
        $artiFiche->setQuantiteServTransitaire($request->get('quantiteTransitaire'));

        $ficheArrivageTransitaire = new FicheArrivageTransitaire();
        $ficheArrivageTransitaire->setInventairePhysique($request->get('inventairePhysique'));
        $ficheArrivageTransitaire->setUser($user);
        $ficheArrivageTransitaire->setArticleFicheArrivage($artiFiche);
        $this->em->persist($ficheArrivageTransitaire);
        $this->em->flush();

        $pathOutside = "/uploads/FicheArrivage/Arrivage=" . $artiFiche->getFicheArrivageAchat()->getId() .
            "/Article=" . $artiFiche->getModel()->getNomMachine() . '/ServTransitaire/OutSideConteneur/';
        $this->uploadMultipleFilesOutsideConteneur($ficheArrivageTransitaire, $request->files->get('imagesOutside'), $pathOutside);

        $pathInside = "/uploads/FicheArrivage/Arrivage=" . $artiFiche->getFicheArrivageAchat()->getId() .
            "/Article=" . $artiFiche->getModel()->getNomMachine() . '/ServTransitaire/InSideConteneur/';
        $this->uploadMultipleFilesInsideConteneur($ficheArrivageTransitaire, $request->files->get('imagesInside'), $pathInside);


        $roles = ['Service Transitaire niveau 1', 'Service Transitaire niveau 0'];
        foreach ($roles as $role) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setSeen(false);
            $notification->setLink('/fiche-arrivage/' . $ficheArrivageTransitaire->getArticleFicheArrivage()->getFicheArrivageAchat()->getId());
            $notification->setName($ficheArrivageTransitaire->getUser()->getFullName() . " à Ajouter la quantite de fiche Arrivage : '" . $ficheArrivageTransitaire->getId() . "' tu dois vérifier cet Arrivage !!");
            $notification->setShowIn($role);
            $notification->setCreatedAt(new \DateTime());
            $notification->setUpdatedAt(new \DateTime());
            $this->em->persist($notification);
            $this->em->flush();
        }
        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json("ok");
    }

    public function uploadMultipleFilesOutsideConteneur(FicheArrivageTransitaire $ficheArrivageTransitaire, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageOutsideConteneur();
            $image->setArticleFicheArrivageTransitaire($ficheArrivageTransitaire);
            $filename = $ficheArrivageTransitaire->getArticleFicheArrivage()->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
            $img->move(
                $uploads_dir,
                $filename
            );
            $image->setName($path . $filename);
            $this->em->persist($image);
            $this->em->flush();

            if (!$image) {
                return $this->json([
                    "error" => "Problem de serveur !!! refresh la page"
                ]);
            }
        }
    }

    public function uploadMultipleFilesInsideConteneur(FicheArrivageTransitaire $ficheArrivageTransitaire, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageInsideConteneur();
            $image->setArticleFicheArrivageTransitaire($ficheArrivageTransitaire);
            $filename = $ficheArrivageTransitaire->getArticleFicheArrivage()->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
            $img->move(
                $uploads_dir,
                $filename
            );
            $image->setName($path . $filename);
            $this->em->persist($image);
            $this->em->flush();

            if (!$image) {
                return $this->json([
                    "error" => "Problem de serveur !!! refresh la page"
                ]);
            }
        }
    }


    /**
     * @Route("/api/addFicheArrivageTransitaireInventairePhysiqueFalse", name="app_add_FicheArrivageTransitaireInventairePhysiqueFalse", methods={"POST"})
     */
    public function addNewFicheArrivageTransitaire(Request $request, UserInterface $user, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $artiFiche = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));


        $ficheArrivageTransitaire = new FicheArrivageTransitaire();
        $ficheArrivageTransitaire->setInventairePhysique($request->get('inventairePhysique'));
        $ficheArrivageTransitaire->setUser($user);
        $ficheArrivageTransitaire->setArticleFicheArrivage($artiFiche);
        $this->em->persist($ficheArrivageTransitaire);
        $this->em->flush();

        $notification = new Notification();
        $notification->setUser($ficheArrivageTransitaire->getUser());
        $notification->setSeen(false);
        $notification->setLink('/fiche-arrivage/' . $ficheArrivageTransitaire->getArticleFicheArrivage()->getFicheArrivageAchat()->getId());
        $notification->setName($ficheArrivageTransitaire->getUser()->getFullName() . " :  Inventaire physique non complet   : '" .
            $ficheArrivageTransitaire->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() . "' tu dois ajouter la quantite de la dume  !!");
        $notification->setShowIn("Service Transitaire niveau 1");
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);

        return $this->json('success');
    }


    /**
     * @Route("/api/addDume", name="app_add_Dume", methods={"POST"})
     */
    public function addNewDume(Request $request, UserInterface $user, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $artiFiche = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));
        $artiFiche->setQuantiteServTransitaire($request->get('quantiteServTransitaire'));

        $dume = new Dume();
        $dume->setUser($user);
        $dume->setArticleFicheArrivage($artiFiche);
        $path = "/uploads/FicheArrivage/Arrivage=" . $artiFiche->getFicheArrivageAchat()->getId() .
            "/Article=" . $artiFiche->getModel()->getNomMachine() . '/ServTransitaire/Dume/';

        $img = $request->files->get('image');
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        $filename = $artiFiche->getModel()->getNomMachine() . "-" . uniqid() . '.' . $img->guessExtension();
        $img->move(
            $uploads_dir,
            $filename
        );
        $dume->setImage($path . $filename);
        $this->em->persist($dume);
        $this->em->flush();


        $notification = new Notification();
        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setLink('/fiche-arrivage/' . $artiFiche->getFicheArrivageAchat()->getId());
        $notification->setName($user->getFullName() . " :  à ajouter New Dume   : '" .
            $artiFiche->getFicheArrivageAchat()->getId() . "' tu dois verifier dume  !!");
        $notification->setShowIn("Service Transitaire niveau 2");
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json($dume);
    }


    /**
     * @Route("/api/updateDume/{id<[0-9]+>}", name="app_update_Dume", methods={"post"})
     */
    public function updateDume(Dume $dume, Request $request, UserInterface $user, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        if ($dume->getImage() != null) {
            unlink($this->getParameter('upload_directory') . $dume->getImage());
        }

        $path = "/uploads/FicheArrivage/Arrivage=" . $dume->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() .
            "/Article=" . $dume->getArticleFicheArrivage()->getModel()->getNomMachine() . '/ServTransitaire/Dume/';

        $img = $request->files->get('image');
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        $filename = $dume->getArticleFicheArrivage()->getModel()->getNomMachine() . "-" . uniqid() . '.' . $img->guessExtension();
        $img->move(
            $uploads_dir,
            $filename
        );
        $dume->setImage($path . $filename);
        $dume->setUser($user);
        $this->em->flush();

        return $this->json($dume);
    }


    /**
     * @Route("/api/notifyMagasinierNv0", name="app_Notification_servMagaNv0", methods={"POST"})
     */
    public function sendNotificationMagasnierNv0(Request $request, UserInterface $user, PublisherInterface $publisher)
    {

        $request = $this->transformJsonBody($request);
        $artiFiche = $this->repoArticleFicheArrivag->find($request->get('articleFicheArrivage'));

        $notification = new Notification();
        $notification->setSeen(false);
        $notification->setUser($user);
        $notification->setLink('/fiche-arrivage/' . $artiFiche->getId());
        $notification->setName("Fiche Arrivage : " . $artiFiche->getId() . " affecter Arrivage a un depot !!!!");
        $notification->setShowIn("Service Magasinier niveau 0");
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json("notify");
    }


    /**
     * @Route("/api/selectDepotFicheArrivage/{id<[0-9]+>}", name="app_selectDepot_FicheArrivage", methods={"POST"})
     */
    public function selectDepot(FicheArrivageAchat $ficheArrivageAchat,UserInterface $user, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $depot = $this->repoDepot->find($request->get('depot'));

        $ficheArrivageAchat->setDepot($depot);
        $this->em->flush();

        $notification = new Notification();
        $notification->setSeen(false);
        $notification->setUser($user);
        $notification->setLink('/fiche-arrivage/' . $ficheArrivageAchat->getId());
        $notification->setName("Fiche Arrivage : " . $ficheArrivageAchat->getId() . " affecter Arrivage a un depot !!!!");
        $notification->setShowIn("Service Magasinier niveau 2");
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);


        return $this->json("notify");
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
