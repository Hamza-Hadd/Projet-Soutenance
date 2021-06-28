<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;





class HomeController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     */
    public function home(): Response
    {
        $artworks = $this->getDoctrine()->getRepository(Artwork::class)->findAll();
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render("home.html.twig", [
            'artworks' => $artworks,
            'users' => $users
        ]);
    }


    /**
     * @Route ("/show/artwork/{artwork_id}", name="showArtwork")
     */
    public function showArtwork(int $artwork_id)
    {
        $artwork =  $this->getDoctrine()->getRepository(Artwork::class)->find($artwork_id);

        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork
        ]);
    }
}
