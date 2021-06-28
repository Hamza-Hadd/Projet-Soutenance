<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\User;
use App\Form\ArtworkType;
use App\Repository\ArtworkRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;


class ArtworkController extends AbstractController
{
    /**
     * @var ArtworkRepository
     */

    private $artworkRepository;

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager, ArtworkRepository $artworkRepository)
    {
        $this->manager = $manager;
        $this->artworkRepository = $artworkRepository;
    }

    /**
     * @Route("addartwork", name="artwork_new", methods={"GET","POST"})
     * @return Response
     */
    public function new(Request $request): Response
    {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artwork);
            $entityManager->flush();

            return $this->redirectToRoute('user_profile');
        }

        return $this->render('artwork/new.html.twig', [
            'artwork' => $artwork,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("showartwork/{id}", name="showArtwork", methods={"GET","POST"}, requirements={"id"="\d+"})
     * @return Response
     */
    public function showArtwork(Request $request, Artwork $artwork, ArtworkRepository $artworkRepository, int $id, EntityManagerInterface $manager): Response
    {
        /*$this->manager = $manager;
        $this->artworkRepository = $artworkRepository;
        $this->getUser();

        $artwork =  $this->getDoctrine()->getRepository(Artwork::class)->find($id);
        $artwork = $this->artworkRepository->find($id);*/
        $Manager = $this->getDoctrine()->getManager();
        $artwork =  $Manager->getRepository(Artwork::class)->find($id);



        return $this->render('artwork/show.html.twig', [
            'artwork' => $artwork,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="artwork_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     * @return Response
     */
    public function edit(Request $request, Artwork $artwork): Response
    {
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('artwork_index');
        }

        return $this->render('artwork/edit.html.twig', [
            'artwork' => $artwork,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("deleteartwork", name="artwork_delete", methods={"POST"})
     * @return Response
     */
    public function delete(Request $request, Artwork $artwork): Response
    {
        if ($this->isCsrfTokenValid('delete' . $artwork->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artwork);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artwork_index');
    }

    public function showStorage(EntityManagerInterface $manager,  ArtworkRepository $artworkRepository)
    {
        return $this->render('artwork/index.html.twig', [
            'artworks' => $artworkRepository->findAll(),
        ]);
    }
}
