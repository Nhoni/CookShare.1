<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(
        User $chossenUser,
        Request $request,
        EntityManagerInterface $manager,
        AuthorizationCheckerInterface $authorizationChecker,
        UserPasswordHasherInterface $hasher
    ): Response {
        if (!$authorizationChecker->isGranted('ROLE_USER') || $this->getUser() !== $chossenUser) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        }

        $form = $this->createForm(UserType::class, $chossenUser);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($chossenUser, $form->getData()->getPlainPassword())) {
                $user = $form->getData();
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Votre profil a bien été mis à jour'
                );

                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Mot de passe invalide'
                );
            }
        }

        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/utilisateur/edition-mot-de-passe/{id}', name: 'user.edit.password', methods: ['GET', 'POST'])]
    public function editPassword(
        User $chossenUser,
        Request $request,
        EntityManagerInterface $manager,
        AuthorizationCheckerInterface $authorizationChecker,
        UserPasswordHasherInterface $hasher
    ): Response {
        if (!$authorizationChecker->isGranted('ROLE_USER') || $this->getUser() !== $chossenUser) {
            throw $this->createAccessDeniedException('Vous n\'avez pas les autorisations nécessaires pour accéder à cette page.');
        }

        $form = $this->createForm(UserPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($hasher->isPasswordValid($chossenUser, $form->getData()['plainPassword'])) {
                $chossenUser->setUpdatedAt(new \DateTimeImmutable());
                $chossenUser->setPlainPassword($form->getData()['newPassword']);

                $this->addFlash(
                    'success',
                    'Votre mot de passe a bien été mis à jour'
                );

                $manager->persist($chossenUser);
                $manager->flush();

                return $this->redirectToRoute('recipe.index');
            } else {
                $this->addFlash(
                    'warning',
                    'Mot de passe invalide'
                );
            }
        }

        return $this->render('pages/user/edit_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
