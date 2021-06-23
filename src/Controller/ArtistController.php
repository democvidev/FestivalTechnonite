<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtistController extends AbstractController
{
    /**
     * @Route("/artist", name="artist_home")
     */
    public function home(CategoryRepository $categoryRepository): Response
    {
        $artists = $this->getDoctrine()->getRepository(Artist::class)->findAll();
        $categories = $categoryRepository->findAll();

        // dd($artists);

        return $this->render('artist/home.html.twig', [
            'artists' => $artists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/artist/{id}", name="artist_view", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function view($id): Response
    {
        $artist = $this->getDoctrine()->getRepository(Artist::class)->findOneBy(['id'=>$id]);

        return $this->render('artist/view.html.twig', [
            'artist' => $artist,
        ]);
    }

    /**
     * @Route("/artist/category/{id}", name="artist_view_by_category", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function viewByCategory($id, ArtistRepository $artistRepository, CategoryRepository $categoryRepository): Response
    {
        $artists = $artistRepository->findByCategory($id);
        $categories = $categoryRepository->findAll();
        // dd($artists);
        return $this->render('artist/home.html.twig', [
            'artists' => $artists,
            'categories' => $categories
        ]);
    }
}
