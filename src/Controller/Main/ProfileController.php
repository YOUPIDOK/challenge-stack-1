<?php

namespace App\Controller\Main;

use App\Entity\User\User;
use App\Form\UpdateProfileType;
use App\Repository\Data\WeightRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[IsGranted('ROLE_CLIENT')]
#[Route('/mon-profil')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'profile')]
    public function profile(WeightRepository $weightRepo): Response
    {
        $weight = $weightRepo->findLastWeightByClient($this->getUser()->getClient());

        return $this->render('pages/profile/profile.html.twig', [
            'weight' => $weight
        ]);
    }

    #[Route('/modifier', name: 'profile_update')]
    public function update(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $em,
        WeightRepository $weightRepo
    ): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        $weight = $weightRepo->findLastWeightByClient($user->getClient());

        $form = $this->createForm(UpdateProfileType::class, $user, [
            'weight' => $weight
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $plainPassword = $user->getPlainPassword();
            if ($plainPassword !== null) {
                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword()));
            }

            $this->addFlash('success', 'Votre profile a bien été modifié.');
            $em->flush();
        }

        return $this->render('pages/profile/profile_update.html.twig', [
            'form' => $form->createView(),
            'weight' => $weight
        ]);
    }
}