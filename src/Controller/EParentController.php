<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ParentType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class EParentController extends AbstractController
{
    /**
     * @Route("/e/parent", name="ecole_parent_home")
     */
    public function index(UserRepository $userRepo): Response
    {
        $parents = $userRepo->findBy(["IsParent" => true]);

        return $this->render('e_parent/index.html.twig', [
            'parents' => $parents,
        ]);
    }
  /**
     * @Route("/e/parent/ajout", name="ecole_parent_ajout")
     */
    public function add(Request $request, ManagerRegistry $managerRegistry, UserPasswordHasherInterface $passwordHash): Response
    {
        $parent = new User();

        $parent->setIsParent(true)
        ->setRoles(["ROLE_PARENT"])
        ->setIsActif(true);

        $form = $this->createForm(ParentType::class, $parent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $passwordClair = $parent->getPassword();

            $passwordCrpt = $passwordHash->hashPassword($parent, $passwordClair);

            $parent->setPassword($passwordCrpt);

            $parent = $form->getData();

            $em = $managerRegistry->getManager();
            $em->persist($parent);
            $em->flush();

            $this->addFlash(
                'message',
                'Parent ajouter avec success'
            );

            return $this->redirectToRoute('ecole_parent_home');
        }

        return $this->render('e_parent/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/e/parent/modifier/{id}", name="ecole_parent_modifier")
     */
    public function edit(User $parent, Request $request, UserRepository $userRepo): Response
    {
        $form = $this->createForm(ParentType::class, $parent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepo->add($parent, true);

            $this->addFlash(
                'message',
                'Parent Modifier avec success'
            );

            return $this->redirectToRoute('ecole_parent_home');
        }

        return $this->render('e_parent/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
