<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Model;
use App\Entity\Nomenclature;
use App\Repository\ModelRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ModelController extends AbstractController
{
    private $em;
    private $repoModel;

    public function __construct(EntityManagerInterface $em, ModelRepository $repoModel)
    {
        $this->em = $em;
        $this->repoModel = $repoModel;
    }


    /**
     * @Route("/api/uploadImageModel/{id<[0-9]+>}", name="app_uploadImage_model", methods={"POST"})
     */
    public function uploadImageModel(Model $model, Request $request, PublisherInterface $publisher)
    {
        $request = $this->transformJsonBody($request);
        $file = $request->files->get('image');
        $DirModelImage = "/uploads/Articles/" . $model->getArticle()->getName() . "/models/";
        $this->uploadModelImage($DirModelImage, $model, $file);

        $this->em->flush();
        return $this->json($model);
    }

    public function uploadModelImage($path, $model, $file)
    {
        if ($file == null || $model == null) {
            return $this->json([
                "error" => "image of Model not uploaded  !!!"
            ]);
        }
        if ($model->getImage() != null) {
            unlink($this->getParameter('upload_directory') . $model->getImage());
        }
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        $filename = $model->getNomMachine() . "-" . uniqid() . '.' . $file->guessExtension();
        $file->move(
            $uploads_dir,
            $filename
        );
        $model->setImage($path . $filename);
        $this->em->persist($model);
        if (!$model) {
            return $this->json([
                "error" => "Problem de serveur !!! refresh la page"
            ]);
        }
    }

    /**
     * @Route("/api/deleteModel/{id<[0-9]+>}", name="app_delete_model", methods={"DELETE"})
     */
    public function deleteModel(Model $model)
    {
        if (!$model) {
            return $this->json([
                "error" => "Model not found"
            ]);
        }
        if ($model->getImage() != null) {
            unlink($this->getParameter('upload_directory') . $model->getImage());
        }
        $this->em->remove($model);
        $this->em->flush();
        return $this->json("Model deleted successfully");

    }

    /**
     * @Route("/api/deleteModelsOfNomenclature/{id<[0-9]+>}", name="app_deleteModelsOfNomenclature_model", methods={"PUT"})
     */
    public function deleteModelsofNomenclatures(Nomenclature $nomenclature, Request $request)
    {
        $request = $this->transformJsonBody($request);
        $model = $this->repoModel->find($request->get('idmodel'));

        $nomenclature->removeModel($model);
        $model->removeNomenclature($nomenclature);
        $this->em->flush();

        return $this->json('removed success');
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
