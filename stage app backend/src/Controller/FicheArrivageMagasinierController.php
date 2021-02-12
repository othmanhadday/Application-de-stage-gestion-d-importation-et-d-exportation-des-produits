<?php

namespace App\Controller;

use App\Entity\FicheArrivageMagasinier;
use App\Entity\FicheArrivageMagasinierManutentionnaire;
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
use App\Repository\FicheArrivageMagasinierRepository;
use App\Repository\FicheArrivageTransitaireRepository;
use App\Repository\ImageInsideConteneurRepository;
use App\Repository\ImageOutsideConteneurRepository;
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
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class FicheArrivageMagasinierController extends AbstractController
{
    private $em;
    private $repoFicheArrivageAchat;
    private $repoFicheArrivageTranstaire;
    private $repoFicheArrivageMagasinier;
    private $repoConteneur;
    private $repoModel;
    private $repoUser;
    private $repoArticleFicheArrivag;
    private $repoImage;
    private $repoImageOutside;
    private $repoImageInside;
    private $repoDepot;
    private $repoCommentaire;

    public function __construct(EntityManagerInterface $em,
                                FicheArrivageAchatRepository $repoFicheArrivage,
                                FicheArrivageTransitaireRepository $repoFicheArrivageTranstaire,
                                FicheArrivageMagasinierRepository $repoFicheArrivageMagasinier,
                                ConteneurRepository $repoConteneur,
                                ModelRepository $repoModel,
                                UserRepository $repoUser,
                                ArticleFicheArrivageAchatRepository $repoArticleFicheArrivag,
                                ImageRepository $repoImage,
                                ImageOutsideConteneurRepository $repoImageOutside,
                                ImageInsideConteneurRepository $repoImageInside,
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
        $this->repoFicheArrivageMagasinier = $repoFicheArrivageMagasinier;
        $this->repoArticleFicheArrivag = $repoArticleFicheArrivag;
        $this->repoImage = $repoImage;
        $this->repoImageOutside = $repoImageOutside;
        $this->repoImageInside = $repoImageInside;
        $this->repoDepot = $repoDepot;
        $this->repoCommentaire = $repoCommentaire;
    }


    /**
     * @Route("/api/addFicheArrivageMagasinier", name="app_add_FcheArrivageMagasinier", methods={"POST"})
     */
    public function addNewArticleFicheArrivageMagasinier(Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);


        $user = $this->repoUser->find($request->get('user'));

        $ficheArrivageMagasinier = $this->repoFicheArrivageMagasinier->find($request->get('ficheArrivageMagasiinier'));

        $ficheArrivageMagasinier->getArticleFicheArrivage()->setQuantiteServMagasinier($request->get('quantiteMagasinier'));
        $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->setQuantiteTotal(
            $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getQuantiteTotal() + $request->get('quantiteMagasinier')
        );
        $ficheArrivageMagasinier->setVerifierMagasinier2(true);

        $path = "/uploads/FicheArrivage/Arrivage=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() .
            "/Article=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getNomMachine() . '/ServiceMagasinier/OutSideConteneur/';
        $this->uploadMultipleFilesOutSide($ficheArrivageMagasinier, $request->files->get('imagesOutSide'), $path);

        $path = "/uploads/FicheArrivage/Arrivage=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() .
            "/Article=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getNomMachine() . '/ServiceMagasinier/InSideConteneur/';
        $this->uploadMultipleFilesInSide($ficheArrivageMagasinier, $request->files->get('imagesInside'), $path);

        $this->em->flush();


        return $this->json($ficheArrivageMagasinier);
    }

    public function uploadMultipleFilesOutSide(FicheArrivageMagasinier $ficheArrivageMagasinier, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageOutsideConteneur();
            $image->setArticleFicheArrivageMagasinier($ficheArrivageMagasinier);
            $filename = $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
            $img->move(
                $uploads_dir,
                $filename
            );
            $image->setName($path . $filename);
            $this->em->persist($image);

            if (!$image) {
                return $this->json([
                    "error" => "Problem de serveur !!! refresh la page"
                ]);
            }
        }
    }

    public function uploadMultipleFilesInSide(FicheArrivageMagasinier $ficheArrivageMagasinier, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageInsideConteneur();
            $image->setArticleFicheArrivageMagasinier($ficheArrivageMagasinier);
            $filename = $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
            $img->move(
                $uploads_dir,
                $filename
            );
            $image->setName($path . $filename);
            $this->em->persist($image);

            if (!$image) {
                return $this->json([
                    "error" => "Problem de serveur !!! refresh la page"
                ]);
            }
        }
    }


    /**
     * @Route("/api/ficheMagasinierQteImageNotValide/{id<[0-9]+>}", name="app_qteImageNotValide_FcheArrivageMagasinier", methods={"PUT"})
     */
    public function manutentionnaireQteImageNotValide(FicheArrivageMagasinier $ficheArrivageMagasinier, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);
        $ficheArrivageMagasinier->setVerifierManitentionnaire(false);
        $user = $this->repoUser->find($request->get('user'));

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setLink('/fiche-arrivage-maga/' . $ficheArrivageMagasinier->getArticleFicheArrivage()->getId());
        $notification->setName($user->getFullName() . " Manutentionnaire n'est pas valide la quantite de la fiche arrivage   : '" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() . "' tu dois vérifier la quantite de cet Arrivage !!");
        $notification->setShowIn("Service Magasinier niveau 2");
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => $notification]));
        $publisher($update);

        return $this->json($ficheArrivageMagasinier);
    }


    /**
     * @Route("/api/updateFicheArrivageMagasinier/{id<[0-9]+>}", name="app_update_FcheArrivageMagasinier", methods={"POST"})
     */
    public function updateArticleFicheArrivageMagasinier(FicheArrivageMagasinier $ficheArrivageMagasinier, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->setQuantiteTotal(
            $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getQuantiteTotal() - $ficheArrivageMagasinier->getArticleFicheArrivage()->getQuantiteServMagasinier()
        );

        $ficheArrivageMagasinier->getArticleFicheArrivage()->setQuantiteServMagasinier($request->get('quantiteMagasinier'));
        $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->setQuantiteTotal(
            $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getQuantiteTotal() + $request->get('quantiteMagasinier')
        );
        $ficheArrivageMagasinier->setVerifierMagasinier2(false);

        $imagesOutside = $this->repoImageOutside->findBy(['articleFicheArrivageMagasinier' => $ficheArrivageMagasinier]);
        $imagesInside = $this->repoImageInside->findBy(['articleFicheArrivageMagasinier' => $ficheArrivageMagasinier]);

        if ($request->files->get('imagesOutSide')) {
            foreach ($imagesOutside as $img) {
                if ($img != null) {
                    unlink($this->getParameter('upload_directory') . $img->getName());
                    $this->em->remove($img);
                }
            }
            $path = "/uploads/FicheArrivage/Arrivage=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() .
                "/Article=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getNomMachine() . '/ServiceMagasinier/OutSideConteneur/';
            $this->uploadMultipleFilesOutSide($ficheArrivageMagasinier, $request->files->get('imagesOutSide'), $path);

        }

        if ($request->files->get('imagesInside')) {
            foreach ($imagesInside as $img) {
                if ($img != null) {
                    unlink($this->getParameter('upload_directory') . $img->getName());
                    $this->em->remove($img);
                }
            }


            $path = "/uploads/FicheArrivage/Arrivage=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getFicheArrivageAchat()->getId() .
                "/Article=" . $ficheArrivageMagasinier->getArticleFicheArrivage()->getModel()->getNomMachine() . '/ServiceMagasinier/InSideConteneur/';
            $this->uploadMultipleFilesInSide($ficheArrivageMagasinier, $request->files->get('imagesInside'), $path);
        }

        $this->em->flush();
        return $this->json($ficheArrivageMagasinier);
    }


    /**
     * @Route("/api/getAllManu", name="app_get_Manutentionnaire", methods={"GET"})
     */
    public function getAllManutentionnaire()
    {
        $services = ['Service Achat', 'Service Après vente'];
        $manu = $this->em->createQuery("
            SELECT u FROM App\Entity\User u
            LEFT JOIN  u.role r
            LEFT JOIN  r.service s
            LEFT JOIN  r.niveau n            
            WHERE s.name LIKE :service1 OR 
            s.name LIKE :service2 AND n.name LIKE :niveau")
            ->setParameter("service1", 'Service Après vente')
            ->setParameter("service2", 'Service Magasinier')
            ->setParameter("niveau", 'manutentionnaire')
            ->execute();

        return $this->json($manu);
    }

    /**
     * @Route("/api/addManuToFicheArrivageMaga", name="app_add_ManutentionnaireToFiArrMaga", methods={"Post"})
     */
    public function addManutentionnaireToFicheArrMaga(Request $request, UserInterface $user, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);

        $manu = $this->repoUser->find($request->get('user'));
        $ficheArrMaga = $this->repoFicheArrivageMagasinier->find($request->get('ficheArrivageMagasinier'));

        $ficheArrMagaMAnu = new FicheArrivageMagasinierManutentionnaire();
        $ficheArrMagaMAnu->setUser($manu);
        $ficheArrMagaMAnu->setFicheArrivageMagasinier($ficheArrMaga);
        $this->em->persist($ficheArrMagaMAnu);
        $this->em->flush();

        $notification = new Notification();
        $notification->setUser($user);
        $notification->setSeen(false);
        $notification->setLink($request->get('link'));
        $notification->setName($request->get('name'));
        $notification->setShowIn($manu->getId());
        $notification->setCreatedAt(new \DateTime());
        $notification->setUpdatedAt(new \DateTime());
        $this->em->persist($notification);
        $this->em->flush();

        $update = new Update("http://localhost:8000/notification",
            json_encode(['message' => "Notificaton sended"]));
        $publisher($update);

        return $this->json($ficheArrMaga->getArticleFicheArrivage());
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
