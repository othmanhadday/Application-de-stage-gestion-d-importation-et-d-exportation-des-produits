<?php

namespace App\Controller;

use App\Entity\ArticleFicheArrivageAchat;
use App\Entity\FicheArrivageAchat;
use App\Entity\Image;
use App\Entity\ImageInsideConteneur;
use App\Entity\ImageOutsideConteneur;
use App\Entity\Nomenclature;
use App\Entity\Notification;
use App\Repository\ArticleFicheArrivageAchatRepository;
use App\Repository\CommantaireRepository;
use App\Repository\ConteneurRepository;
use App\Repository\FicheArrivageAchatRepository;
use App\Repository\ImageRepository;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class FicheArrivageAchatController extends AbstractController
{
    private $em;
    private $repoFicheArrivage;
    private $repoConteneur;
    private $repoModel;
    private $repoArticleFicheArrivag;
    private $repoImage;
    private $repoCommentaire;

    public function __construct(EntityManagerInterface $em,
                                FicheArrivageAchatRepository $repoFicheArrivage,
                                ConteneurRepository $repoConteneur,
                                ModelRepository $repoModel,
                                ArticleFicheArrivageAchatRepository $repoArticleFicheArrivag,
                                ImageRepository $repoImage,
                                CommantaireRepository $repoCommentaire
    )
    {
        $this->em = $em;
        $this->repoModel = $repoModel;
        $this->repoConteneur = $repoConteneur;
        $this->repoFicheArrivage = $repoFicheArrivage;
        $this->repoArticleFicheArrivag = $repoArticleFicheArrivag;
        $this->repoImage = $repoImage;
        $this->repoCommentaire = $repoCommentaire;
    }


//Add Images To Article FicheArrivage OutSide Conteneur

    /**
     * @Route("/api/uploadImagesOutSideConteneurToArticleFicheArrivage/{id<[0-9]+>}", name = "app_uploadImage_ArticleFicheArrivage", methods = {"POST"})
     */
    public function uploadImagesOutSideConteneurToArticleFicheArrivage(ArticleFicheArrivageAchat $articleFicheArrivageAchat, Request $request)
    {
        $request = $this->transformJsonBody($request);
        $path = "/uploads/FicheArrivage/Arrivage=" . $articleFicheArrivageAchat->getFicheArrivageAchat()->getId() .
            "/Article=" . $articleFicheArrivageAchat->getModel()->getNomMachine() . '/ServiceAchats/OutSideConteneur/';

        $this->uploadMultipleFiles($articleFicheArrivageAchat, $request->files->get('images'), $path);

        $this->em->flush();

        return $this->json($articleFicheArrivageAchat);
    }

    public function uploadMultipleFiles($artFcheArrivage, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageOutsideConteneur();
            $image->setArticleFicheArrivageAchat($artFcheArrivage);
            $filename = $artFcheArrivage->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
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



    //Add Images To Article FicheArrivage INSide Conteneur

    /**
     * @Route("/api/uploadImagesInSideConteneurToArticleFicheArrivage/{id<[0-9]+>}", name = "app_uploadImageInsideConteneur_ArticleFicheArrivage", methods = {"POST"})
     */
    public function uploadImagesInSideConteneurToArticleFicheArrivage(ArticleFicheArrivageAchat $articleFicheArrivageAchat, Request $request)
    {
        $request = $this->transformJsonBody($request);
        $path = "/uploads/FicheArrivage/Arrivage=" . $articleFicheArrivageAchat->getFicheArrivageAchat()->getId() .
            "/Article=" . $articleFicheArrivageAchat->getModel()->getNomMachine() . '/ServiceAchats/InSideConteneur/';

        $this->uploadMultipleFilesInSideConteneur($articleFicheArrivageAchat, $request->files->get('images'), $path);

        $this->em->flush();

        return $this->json($articleFicheArrivageAchat);
    }

    public function uploadMultipleFilesInSideConteneur($artFcheArrivage, $images, $path)
    {
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($images as $img) {
            $image = new ImageInsideConteneur();
            $image->setArticleFicheArrivageAchat($artFcheArrivage);
            $filename = $artFcheArrivage->getModel()->getRefMachine() . "-" . uniqid() . '.' . $img->guessExtension();
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
     * @Route("/api/deleteFicheArrivage/{id<[0-9]+>}", name = "app_delete_ficheArrivage", methods = {"DELETE"})
     */
    public function deleteFicheArrivage(FicheArrivageAchat $ficheArrivageAchat)
    {
        $filesystem = new Filesystem();

        if (!$ficheArrivageAchat) {
            return $this->json([
                "error" => "Fiche not found"
            ]);
        }
        $filesystem->remove($this->getParameter('upload_directory') . "/uploads/FicheArrivage/Arrivage=" .
            $ficheArrivageAchat->getId());

        $this->em->remove($ficheArrivageAchat);
        $this->em->flush();

        return $this->json(" deleted successfully");

    }

    /**
     * @Route("/api/deleteArticleFicheArrivage/{id<[0-9]+>}", name = "app_delete_artcleFicheArrivage", methods = {"DELETE"})
     */
    public function deleteArticleFicheArrivage(ArticleFicheArrivageAchat $articleFicheArrivageAchat)
    {
        $filesystem = new Filesystem();

        if (!$articleFicheArrivageAchat) {
            return $this->json([
                "error" => "Fiche not found"
            ]);
        }
        $filesystem->remove($this->getParameter('upload_directory') . "/uploads/FicheArrivage/Arrivage=" .
            $articleFicheArrivageAchat->getFicheArrivageAchat()->getId() . "/Article=" . $articleFicheArrivageAchat->getModel()->getNomMachine());

        $this->em->remove($articleFicheArrivageAchat);
        $this->em->flush();

        return $this->json(" deleted successfully");

    }


    //Delete Image outSideConteneur

    /**
     * @Route("/api/deleteImageOutSideConteneurArticleFicheArrivage/{id<[0-9]+>}", name = "app_deleteImage_ArticleFicheArrivage", methods = {"DELETE"})
     */
    public function deleteImageFromArticleFicheArrivage(ImageOutsideConteneur $image)
    {
        if (!$image) {
            return $this->json([
                "error" => "mage not found"
            ]);
        }

        unlink($this->getParameter('upload_directory') . $image->getName());
        $this->em->remove($image);
        $this->em->flush();

        return $this->json(" deleted successfully");
    }

    //Delete Image InSideConteneur

    /**
     * @Route("/api/deleteImageInSideConteneurArticleFicheArrivage/{id<[0-9]+>}", name = "app_deleteImageInside_ArticleFicheArrivage", methods = {"DELETE"})
     */
    public function deleteImageInsideFromArticleFicheArrivage(ImageInsideConteneur $image)
    {
        if (!$image) {
            return $this->json([
                "error" => "mage not found"
            ]);
        }

        unlink($this->getParameter('upload_directory') . $image->getName());
        $this->em->remove($image);
        $this->em->flush();

        return $this->json(" deleted successfully");
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
