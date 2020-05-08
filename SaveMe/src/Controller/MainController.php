<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Photo;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends Controller
{

    /**
     * @Route("/", name="main_accueil")
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function accueil(EntityManagerInterface $entityManager){
        $photoRepository = $entityManager->getRepository(Photo::class);
        $photos = $photoRepository->accueil();

        $categoryRepository = $entityManager->getRepository(Category::class);
        $categories = $categoryRepository->findAll();

        return $this->render('main/accueil.html.twig', ['photos' =>$photos, 'categories'=>$categories]);
    }


    /**
     * @Route("/main", name="main")
     */
    public function index()
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
