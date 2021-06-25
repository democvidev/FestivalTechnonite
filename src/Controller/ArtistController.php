<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtistController extends AbstractController
{
    /**
     * @Route("/artist", name="artist_home")
     */
    public function home(CategoryRepository $categoryRepository, ArtistRepository $artistRepository, Request $request, PaginatorInterface $paginator): Response
    {
        // recupère le tableau remplie des objets par injection de dépendances
        $data = $artistRepository->findAll();
        $categories = $categoryRepository->findAll(); 
        $artists = $paginator->paginate(
            $data,
            $request->query->get('page', 1), // nr de la page en cours, par défaut la page 1
            9 // artists par page
        );

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
    public function viewByCategory($id, ArtistRepository $artistRepository, CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $artistRepository->findByCategory($id);
        $categories = $categoryRepository->findAll();
        $artists = $paginator->paginate(
            $data,
            $request->query->get('page', 1), // nr de la page en cours, par défaut la page 1
            9 // artists par page
        );
        // dd($artists);
        return $this->render('artist/home.html.twig', [
            'artists' => $artists,
            'categories' => $categories
        ]);
    }
}
