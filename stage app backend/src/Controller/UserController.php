<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Post;
use App\Entity\ServiceNiveau;
use App\Entity\User;
use App\Repository\ImageRepository;
use App\Repository\NiveauRepository;
use App\Repository\ServiceNiveauRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class UserController extends AbstractController
{
    private $em;
    private $repo;
    private $repoSer;
    private $repoNiv;
    private $repoUser;
    private $repoImg;

    public function __construct(EntityManagerInterface $em,
                                UserRepository $repoUser,
                                ServiceNiveauRepository $repo,
                                ServiceRepository $repoSer,
                                NiveauRepository $repoNiv,
                                ImageRepository $rempImg
    )
    {
        $this->em = $em;
        $this->repo = $repo;
        $this->repoSer = $repoSer;
        $this->repoNiv = $repoNiv;
        $this->repoUser = $repoUser;
        $this->repoImg = $rempImg;
    }

    protected $allDataRecieved = array();

    protected function getDataFormRequest(Request $request)
    {
        $request = $this->transformJsonBody($request);

        $allDataReceived = array();
        $allDataReceived['cin'] = $request->get('cin');
        $allDataReceived['email'] = $request->get('email');
        $allDataReceived['fullName'] = $request->get('fullName');
        $allDataReceived['password'] = $request->get('password');
        $allDataReceived['niveauScolaire'] = $request->get('niveauScolaire');
        $allDataReceived['tel'] = $request->get('tel');
        $allDataReceived['imageUser'] = $request->files->get('imageUser');
        $allDataReceived['imageCin'] = $request->files->get('imageCin');
        $allDataReceived['imageDiplomes'] = $request->files->get('imageDiplomes');
        $allDataReceived['service'] = $request->get('service');
        $allDataReceived['niveau'] = $request->get('niveau');
        return $allDataReceived;
    }

    /**
     * @Route("/register", name="app_register", methods={"POST"})
     */
    public function register(Request $request, UserPasswordEncoderInterface $encoder, PublisherInterface $publisher)
    {
        $this->allDataRecieved = $this->getDataFormRequest($request);
        if ($this->allDataRecieved['cin'] == null || $this->allDataRecieved['email'] == null || $this->allDataRecieved['fullName'] == null ||
            $this->allDataRecieved['password'] == null || $this->allDataRecieved['niveauScolaire'] == null || $this->allDataRecieved['tel'] == null ||
            $this->allDataRecieved['imageUser'] == null || $this->allDataRecieved['imageDiplomes'] == null || $this->allDataRecieved['imageCin'] == null ||
            $this->allDataRecieved['service'] == null || $this->allDataRecieved['niveau'] == null
        ) {
            return $this->json([
                "error" => "fill all inputs  !!!!"
            ]);
        }

        $userEmail = $this->repoUser->findOneBy([
            "email" => $this->allDataRecieved['email']
        ]);
        if ($userEmail) {
            return $this->json([
                "error" => "Email Already exist !!!!"
            ]);
        }

        $service = $this->repoSer->find($this->allDataRecieved['service']);
        $niveau = $this->repoNiv->find($this->allDataRecieved['niveau']);

        $serNiv = new ServiceNiveau();
        $serNiv->setNiveau($niveau);
        $serNiv->setService($service);
        $this->em->persist($serNiv);
        if (!$serNiv) {
            return $this->json([
                "error" => "Problem de serveur !!! refresh la page"
            ]);
        }

        $user = new User($this->allDataRecieved['email']);
        $user->setPassword($encoder->encodePassword($user, $this->allDataRecieved['password']));
        $user->setEmail($this->allDataRecieved['email']);
        $user->setFullName($this->allDataRecieved['fullName']);
        $user->setCin($this->allDataRecieved['cin']);
        $user->setTel($this->allDataRecieved['tel']);
        $user->setNiveauScolaire($this->allDataRecieved['niveauScolaire']);
        $user->setRole($serNiv);
        $this->em->persist($user);
        if (!$user) {
            return $this->json([
                "error" => "Problem de serveur !!! refresh la page"
            ]);
        }

        $DirUserImage = "/uploads/Users/" . $user->getEmail() . "/personnel/";
        $DirUserCin = "/uploads/Users/" . $user->getEmail() . "/Cin/";
        $DirUserDiplome = "/uploads/Users/" . $user->getEmail() . "/Diplomes/";
        $this->uploadUserImage($DirUserImage, $user, $this->allDataRecieved['imageUser']);
        $this->uploadMultipleFile($DirUserCin, $user, $this->allDataRecieved['imageCin']);
        $this->uploadMultipleFile($DirUserDiplome, $user, $this->allDataRecieved['imageDiplomes']);

        $this->em->flush();
        $users = $this->repoUser->findAll();
        $update = new Update("http://localhost:8000/ping",
            json_encode(['message' => $users]));
        $publisher($update);

        return $this->json([
            "success" => "succes"
        ]);
    }

    public function uploadUserImage($path, $user, $file)
    {
        if ($file == null || $user == null) {
            return $this->json([
                "error" => "image of user not uploaded  !!!"
            ]);
        }
        $image = new Image();
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        $filename = $user->getUsername() . "-" . uniqid() . '.' . $file->guessExtension();
        $file->move(
            $uploads_dir,
            $filename
        );
        $image->setName($path . $filename);
        $image->setUser($user);
        $this->em->persist($image);
        if (!$image) {
            return $this->json([
                "error" => "Problem de serveur !!! refresh la page"
            ]);
        }
    }

    public function uploadMultipleFile($path, $user, $files)
    {
        if ($files == null || $user == null) {
            return $this->json([
                "error" => "image of user not uploaded  !!!"
            ]);
        }
        $uploads_dir = $this->getParameter('upload_directory') . $path;
        foreach ($files as $file) {
            $image = new Image();
            $filename = $user->getUsername() . "-" . uniqid() . '.' . $file->guessExtension();
            $file->move(
                $uploads_dir,
                $filename
            );
            $image->setName($path . $filename);
            $image->setUser($user);
            $this->em->persist($image);
            if (!$image) {
                return $this->json([
                    "error" => "Problem de serveur !!! refresh la page"
                ]);
            }
        }
    }


    /**
     * @Route("/roles", name="app_roles", methods={"GET"})
     */
    public function role()
    {
        $services = $this->repoSer->findAll();
        $niveaux = $this->repoNiv->findAll();

        return $this->json([
            "services" => $services,
            "niveaux" => $niveaux
        ]);
    }


    /**
     * @Route("/api/user/{id<[0-9]+>}/edit", name="app_edit_user", methods={"PUT"})
     */
    public function edit(User $user, Request $request, UserPasswordEncoderInterface $encoder)
    {


        if (!$user) {
            return $this->json([
                "error" => "User not found"
            ]);
        }

        $this->allDataRecieved = $this->getDataFormRequest($request);
//        $request = $this->transformJsonBody($request);
        $this->allDataRecieved['roles'] = $request->get('roles');


        if ($this->allDataRecieved['cin'] == null || $this->allDataRecieved['email'] == null || $this->allDataRecieved['fullName'] == null ||
            $this->allDataRecieved['password'] == null || $this->allDataRecieved['niveauScolaire'] == null || $this->allDataRecieved['tel'] == null ||
            $this->allDataRecieved['roles'] == null
        ) {
            return $this->json([
                "error" => "fill all inputs  !!!!"
            ]);
        }

        //var_dump($this->allDataRecieved["roles"]["service"]['id']);
        $service = $this->repoSer->find($this->allDataRecieved["roles"]["service"]['id']);
        $niveau = $this->repoNiv->find($this->allDataRecieved["roles"]["niveau"]['id']);

        $serNiv = $this->repo->find($user->getRole()->getId());
        $serNiv->setNiveau($niveau);
        $serNiv->setService($service);

        if ($user->getPassword() != $this->allDataRecieved['password']) {
            $user->setPassword($encoder->encodePassword($user, $this->allDataRecieved['password']));
        }

        $user->setFullName($this->allDataRecieved["fullName"]);
        $user->setCin($this->allDataRecieved["cin"]);
        $user->setTel($this->allDataRecieved["tel"]);
        $user->setNiveauScolaire($this->allDataRecieved["niveauScolaire"]);
        $user->setRole($serNiv);
        $this->em->flush();

        return $this->json([
            "success" => $user
        ]);

    }

    /**
     * @Route("/api/user/{id<[0-9]+>}/updateUserImage", name="app_update_user_image", methods={"POST"})
     */
    public function updateImageOfUser(User $user, Request $request)
    {
        $this->allDataRecieved = $this->getDataFormRequest($request);
        $images = $this->repoImg->findBy(['user' => $user->getId()]);
        foreach ($images as $img) {
            if (strpos($img->getName(), 'personnel')) {
                if ($this->allDataRecieved['imageUser'] != $img->getName()) {
                    unlink($this->getParameter('upload_directory') . $img->getName());
                    $this->em->remove($img);
                    $this->em->flush();
                }
            }
        }
        $DirUserImage = "/uploads/Users/" . $user->getEmail() . "/personnel/";
        $this->uploadUserImage($DirUserImage, $user, $this->allDataRecieved['imageUser']);
        $this->em->flush();
        return $this->json($user);
    }

    /**
     * @Route("/api/user/{id<[0-9]+>}/updateCinImages", name="app_update_cin_images", methods={"POST"})
     */
    public function updateImagesOfCin(User $user, Request $request)
    {
        $this->allDataRecieved = $this->getDataFormRequest($request);
        $images = $this->repoImg->findBy(['user' => $user->getId()]);
        foreach ($images as $img) {
            if (strpos($img->getName(), 'Cin')) {
                if ($this->allDataRecieved['imageCin'] != $img->getName()) {
                    unlink($this->getParameter('upload_directory') . $img->getName());
                    $this->em->remove($img);
                    $this->em->flush();
                }
            }
        }
        $DirUserCin = "/uploads/Users/" . $user->getEmail() . "/Cin/";
        $this->uploadMultipleFile($DirUserCin, $user, $this->allDataRecieved['imageCin']);
        $this->em->flush();
        return $this->json($user);
    }

    /**
     * @Route("/api/user/{id<[0-9]+>}/updateDiplomesImages", name="app_update_diplomes_images", methods={"POST"})
     */
    public function updateImagesOfDiplomes(User $user, Request $request)
    {
        $this->allDataRecieved = $this->getDataFormRequest($request);
        $images = $this->repoImg->findBy(['user' => $user->getId()]);
        foreach ($images as $img) {
            if (strpos($img->getName(), 'Diplomes')) {
                if ($this->allDataRecieved['imageDiplomes'] != $img->getName()) {
                    unlink($this->getParameter('upload_directory') . $img->getName());
                    $this->em->remove($img);
                    $this->em->flush();
                }
            }
        }
        $DirUserDiplome = "/uploads/Users/" . $user->getEmail() . "/Diplomes/";
        $this->uploadMultipleFile($DirUserDiplome, $user, $this->allDataRecieved['imageDiplomes']);
        $this->em->flush();
        return $this->json($user);
    }

    /**
     * @Route("/api/user/{id<[0-9]+>}/delete", name="app_delete_user", methods={"DELETE"})
     */
    public function deleteUser(User $user)
    {

        if (!$user) {
            return $this->json([
                "error" => "User not found"
            ]);
        }

        $images = $this->repoImg->findBy(['user' => $user]);
        foreach ($images as $img) {
            unlink($this->getParameter('upload_directory') . $img->getName());
        }
        $DirUserImage = "/uploads/Users/" . $user->getEmail() . "/personnel/";
        $DirUserCin = "/uploads/Users/" . $user->getEmail() . "/Cin/";
        $DirUserDiplome = "/uploads/Users/" . $user->getEmail() . "/Diplomes/";
        rmdir($this->getParameter('upload_directory') . $DirUserImage);
        rmdir($this->getParameter('upload_directory') . $DirUserCin);
        rmdir($this->getParameter('upload_directory') . $DirUserDiplome);
        rmdir($this->getParameter('upload_directory') . "/uploads/Users/" . $user->getEmail());
        $serNiv = $this->repo->find($user->getRole()->getId());
        $this->em->remove($serNiv);
        $this->em->flush();


        return $this->json("Post deleted successfully");
    }


    /**
     * @Route("/api/Currentuser", name="app_current_user", methods={"GET"})
     */
    public function getCurrentUser(UserInterface $user)
    {
        return $this->json($user);
    }

    /**
     * @Route("/api/changePwd/{id<[0-9]+>}", name="app_changePwd_user", methods={"POST"})
     */
    public function changePassword(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $this->transformJsonBody($request);
       // dd($request->get("password"));
        $user->setPassword($encoder->encodePassword($user,$request->get("password")));
        $this->em->flush();
        return $this->json("success");
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
