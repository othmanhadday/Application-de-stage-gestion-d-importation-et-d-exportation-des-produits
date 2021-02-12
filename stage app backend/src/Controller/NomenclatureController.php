<?php

namespace App\Controller;

use App\Entity\Model;
use App\Entity\Nomenclature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Routing\Annotation\Route;

class NomenclatureController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/api/uploadImageNomenclature/{id<[0-9]+>}", name="app_uploadImage_nomenclature", methods={"POST"})
     */
    public function uploadImageNomenclature(Nomenclature $nomenclature, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);
        $file = $request->files->get('image');
        $DirModelImage = "/uploads/Nomenclature/" . $nomenclature->getDesignation()."/";
        $this->uploadImage($DirModelImage, $nomenclature, $file);

        $this->em->flush();
        return $this->json($nomenclature);
    }

    public function uploadImage($path, $nomenclature, $file)
    {
        if ($file == null || $nomenclature == null) {
            return $this->json([
                "error" => "image of Nomenclature not uploaded  !!!"
            ]);
        }
        if ($nomenclature->getImage() != null) {
            unlink($this->getParameter('upload_directory') . $nomenclature->getImage());
        }
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        $filename = $nomenclature->getDesignation() . "-" . uniqid() . '.' . $file->guessExtension();
        $file->move(
            $uploads_dir,
            $filename
        );
        $nomenclature->setImage($path . $filename);
        $this->em->persist($nomenclature);
        if (!$nomenclature) {
            return $this->json([
                "error" => "Problem de serveur !!! refresh la page"
            ]);
        }
    }

    /**
     * @Route("/api/deleteNomenclature/{id<[0-9]+>}", name="app_delete_nomenclature", methods={"DELETE"})
     */
    public function deleteModel(Nomenclature $nomenclature)
    {
        if (!$nomenclature) {
            return $this->json([
                "error" => "Model not found"
            ]);
        }
        if ($nomenclature->getImage() != null) {
            unlink($this->getParameter('upload_directory') . $nomenclature->getImage());
        }
        $this->em->remove($nomenclature);
        $this->em->flush();
        return $this->json("Nomenclature deleted successfully");

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
