<?php

namespace App\Controller\Main;

use App\Entity\Data\Weight;
use App\Entity\User\User;
use App\Form\RegisterType;
use App\Security\Main\MainAuthenticator;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Transport\Smtp\Auth\AuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegisterController extends AbstractController
{
    #[Route(path: '/creer-un-compte', name: 'register')]
    public function registerClient(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $userPasswordHasher,
        TokenStorageInterface $tokenStorage
    ): Response
    {
        if ($this->getUser() != null) {
            $this->addFlash('success', 'Vous êtes déjà connecté.');
            return $this->redirectToRoute('homepage');
        }

        $user = new User();

        $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPlainPassword() ?? ''));
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em->persist($user);
            $em->flush();

            $weight = (new Weight())
                ->setClient($user->getClient())
                ->setWeight($form->get('weight')->getData())
                ->setDate(new DateTime('now'));

            $em->persist($weight);
            $em->flush();

            return $this->redirectToRoute('login');
        }

        return $this->render('pages/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
