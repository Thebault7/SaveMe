<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Photo;
use App\Entity\User;
use App\Form\PhotoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/photo", name="photo_")
 */
class PhotoController extends Controller
{


    /**
     * @Route("/add", name="add")
     */
    public function new(Request $request, EntityManagerInterface $entityManager)
    {
        $photo = new Photo();
        $form = $this->createForm(PhotoType::class, $photo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = transliterator_transliterate(
                    'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                    $originalFilename
                );
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $photo->setTitre($newFilename);
            }

            // ... persist the $product variable or any other work
            $photo->setDate(new \DateTime());
            $photo->setProprietaire($this->getUser());
            $entityManager->persist($photo);
            $entityManager->flush();

            return $this->redirect($this->generateUrl('main_accueil'));
        }

        return $this->render(
            'photo/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/all/{page}", name="all", requirements={"page": "\d+"})
     */
    public function getAllPhotos(EntityManagerInterface $entityManager, $page=0){


        $limit  = 8;

        $photosRepository = $entityManager->getRepository(Photo::class);
        $photos = $photosRepository->findPhotosByPage($page, $limit);

        // nb total de series (hors limite de la page)
        $nbTotalPhotos = count($photos);

        // ceil — Arrondit au nombre supérieur
        $nbPage = ceil($nbTotalPhotos/$limit);

        return $this->render('photo/viewall.html.twig', compact('photos', 'page', 'nbPage'));
    }

    /**
     * @Route("/me", name="me")
     */
    public function getMyPhotos(EntityManagerInterface $entityManager)
    {

        $photosRepository = $entityManager->getRepository(Photo::class);
        $photos = $photosRepository->findBy(['proprietaire' => $this->getUser()]);

        return $this->render('photo/myphotos.html.twig', compact('photos'));
    }

    /**
     * @Route("/detail/{id}", name="detail", requirements={"id": "\d+"})
     *
     */
    public function detail($id, EntityManagerInterface $entityManager)
    {
        $photoRepository = $entityManager->getRepository(Photo::class);
        $photo = $photoRepository->find($id);
        $userRepository = $entityManager->getRepository(User::class);
        $proprietaire = $userRepository->find($photo->getProprietaire());
        $categoryRepository = $entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($photo->getCategory());
        $user = $this->getUser();

        return $this->render(
            'photo/detail.html.twig',
            compact('photo', 'proprietaire', 'category', 'user')
        );
    }

    /**
     * @Route("/delete/{id}", name="delete", requirements={"id": "\d+"})
     */
    public function delete($id, EntityManagerInterface $entityManager)
    {
        $photoRepository = $entityManager->getRepository(Photo::class);
        $photo = $photoRepository->find($id);

        $filesystem = new Filesystem();
        try {
            $filesystem->remove('assets/img/'.$photo->getTitre());
        } catch (IOExceptionInterface $exception) {
            echo "Une Erreur est apparue lors de la suppression du fichier  ".$exception->getPath();
        }

        $entityManager->remove($photo);
        $entityManager->flush();

        return $this->redirect($this->generateUrl('main_accueil'));
    }

    /**
     * @Route("/geolocaliser/{id}", name="geolocaliser", requirements={"id": "\d+"})
     */
    public function geolocaliser($id, EntityManagerInterface $entityManager)
    {
        $photoRepository = $entityManager->getRepository(Photo::class);
        $photo = $photoRepository->find($id);

        $coord_GPS = exif_read_data('C:/wamp64/www/SaveMe/public/assets/img/'.$photo->getTitre());

        if (isset($coord_GPS['GPSLatitude'][0]) && isset($coord_GPS['GPSLongitude'][0])) {
            $latitude_degres = explode('/', $coord_GPS['GPSLatitude'][0])[0];
            $latitude_minutes = explode('/', $coord_GPS['GPSLatitude'][1])[0];
            $latitude_secondes = explode('/', $coord_GPS['GPSLatitude'][2])[0];

            $longitude_degres = explode('/', $coord_GPS['GPSLongitude'][0])[0];
            $longitude_minutes = explode('/', $coord_GPS['GPSLongitude'][1])[0];
            $longitude_secondes = explode('/', $coord_GPS['GPSLongitude'][2])[0];

            $latitude = ($latitude_degres + ($latitude_minutes + $latitude_secondes / 60) / 60);
            $longitude = -($longitude_degres + ($longitude_minutes + $longitude_secondes / 60) / 60);
        } else {
            $latitude = false;
            $longitude = false;
        }
        return $this->render('photo/geolocalisation.html.twig', compact('latitude', 'longitude'));
    }

}
