<?php

namespace App\Controller;

use App\Repository\ArtistRepository;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArtistController extends AbstractController
{

    private $artistRepository;

    public function __construct(ArtistRepository $artistRepository)
    {
        $this->artistRepository = $artistRepository;
    }

    /**
     * @Route("/artist", name="artist_home")
     * @Route("/artist/category/{slug}", name="artist_view_by_category", methods={"GET"})
     */
    public function home(CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator, $slug=null): Response
    {
        // recupère le tableau remplie des objets par injection de dépendances
        $data = $slug === null ?
        $this->artistRepository->findAll() :
        $this->artistRepository->findByCategorySlug($slug);
        $categories = $categoryRepository->findAll(); 
        $artists = $paginator->paginate(
            $data,
            $request->query->get('page', 1), // nr de la page en cours, par défaut la page 1
            9 // artists par page
        );
        return $this->render('artist/home.html.twig', [
            'artists' => $artists,
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/artist/{slug}", name="artist_view", methods={"GET"})
     */
    public function view($slug): Response
    {
        $artist = $this->artistRepository->findOneBy(['slug'=>$slug]);
        return $this->render('artist/view.html.twig', [
            'artist' => $artist,
        ]);
    }   
}
