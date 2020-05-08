<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends Controller
{

//    /**
//     * @Route("/", name="filter")
//     * @param $id
//     * @param EntityManagerInterface $entityManager
//     * @return \Symfony\Component\HttpFoundation\Response
//     */
//    public function filtrer(EntityManagerInterface $entityManager, Request $request)
//    {
//        $id = $request->query->get('categorie');
//        dump($id);
//        die();
//        $photoRepository = $entityManager->getRepository(PhotoType::class);
//        $photos = $photoRepository->findBy(['category' => $id]);
//
//        // By(['category'=>$id]);
//        return $this->render('category/photosfiltres.html.twig', compact('photos'));
//    }

    /**
     * @Route("/{id}", name="filter")
     * @param $id
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function filtrer($id = 0, EntityManagerInterface $entityManager, Request $request)
    {

        $photoRepository = $entityManager->getRepository(Photo::class);
        $photos = $photoRepository->findBy(['category' => $id]);

        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        return $this->render('category/photosfiltres.html.twig', compact('photos', 'categories'));
    }

}

