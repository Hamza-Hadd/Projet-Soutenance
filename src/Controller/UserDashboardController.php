<?php

namespace App\Controller;

use App\Entity\Artwork;
use App\Entity\User;
use App\Form\EditProfileType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Response;


class UserDashboardController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/profileuser/{id}", name="user_profile", requirements={"id"="\d+"}, methods={"GET","POST"})
     * @return Response
     */
    public function showProfil(
        EntityManagerInterface $manager,
        UserRepository $userRepository,
        int $id
    ) {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
        $this->getUser();

        $artworks = $this->getDoctrine()->getRepository(Artwork::class)->findAll();
        //return $this->render('UserDashboard/dashboard.html.twig');


        return $this->render('userdashboard/profil.html.twig', [
            'artworks' => $artworks,

        ]);
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route("editprofile", name="edit_profile", requirements={"id"="\d+"}, methods={"GET","POST"})
     * @return Response
     */
    public function editProfils(Request $request, UserRepository $user /*UserPasswordEncoderInterface $passwordEncoder*/): Response
    {
        $user = $this->getDoctrine()->getRepository(User::class);

        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        //$userRepository = $this->getDoctrine()->getRepository(User::class);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            /*$user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );*/
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('message', 'Profil mis a jour');

            return $this->redirectToRoute('user_profile');
        }


        return $this->render('UserDashboard/editProfile.html.twig', ['form_editProfile' => $form->createView()]);
    }
}
