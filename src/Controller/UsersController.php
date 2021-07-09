<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Categories;
use App\Entity\Prestations;
use App\Form\AddCategoryType;
use App\Form\AddUserInfosType;
use App\Repository\AppointmentsRepository;
use App\Form\AddPrestationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="users")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/mes-infos/{id}", name="userInfos")
     */
    public function infosUser($id, Users $user, EntityManagerInterface $manager, Request $request)
    {


        if ($id === strval($this->getUser()->getId())) {

            $form = $this->createForm(AddUserInfosType::class, $user);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('home');
            }
            return $this->render("users/infos.html.twig", [
                'user' => $form->createView(),
                'test' => 'testtest' . $id
            ]);
        } else {

            return $this->render("users/infos.html.twig", [
                'test' => 'error'
            ]);
        }
    }
    /**
     * @Route("/Agenda/{id}", name="agenda")
     */
    public function Agenda($id, AppointmentsRepository $appointmentsRepository){


        if ($id === strval($this->getUser()->getId())) {

            return $this->render("users/agenda.html.twig", [
                'user' => $id,
            ]);
        } else {

            return $this->render("users/agenda.html.twig", [
                'test' => 'error'
            ]);
        }
    }


    /**
     * @Route("/prestations/{id}", name="prestations")
     */
    public function Prestations($id, Users $user, EntityManagerInterface $manager, Request $request)
    {
        $category = new Categories();
        $presta = new Prestations();

        if ($id === strval($this->getUser()->getId())) {

            $form = $this->createForm(AddPrestationType::class, $presta);
            $form2 = $this->createForm(AddCategoryType::class, $category);

            $form->handleRequest($request);
            $form2->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $presta->setUsers($user);
                $manager->persist($presta);
                $manager->flush();
                return $this->redirectToRoute('prestations', [
                    'id' => $id
                ]);
            }

            if ($form2->isSubmitted() && $form2->isValid()) {
                $category->setUsers($user);
                $manager->persist($category);
                $manager->flush();
                return $this->redirectToRoute('prestations', [
                    'id' => $id
                ]);
            }
            return $this->render("users/prestations.html.twig", [
                'addCategory' => $form2->createView(),
                'category' =>  $user->getCategory(),
                'addPresta' => $form->createView(),
                'presta' =>  $user->getPrestation(),
                'prestations' => $presta
            ]);
        } else {

            return $this->render("users/prestations.html.twig", [
                'test' => 'error'
            ]);
        }
    }

    /**
     * @Route("/category/{id}", name="category_edit")
     */
    public function editCategory(Request $request, Categories $category): Response
    {
        $form = $this->createForm(AddCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_edit', [
                'id' => $category->getId()
            ]);
        }

        return $this->render('users/category_edit.html.twig', [
            'addCategory' => $form->createView(),
            'category' =>  $category,
            'form' => $form->createView(),
            
        ]);
    }

    /**
     * @Route("/prestation/{id}", name="prestation_edit")
     */
    public function editPrestation(Request $request, Prestations $presta): Response
    {
        $form = $this->createForm(AddPrestationType::class, $presta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('prestation_edit', [
                'id' => $presta->getId()
            ]);
        }

        return $this->render('users/prestation_edit.html.twig', [
            'addPresta' => $form->createView(),
            'presta' =>  $presta,
            'form' => $form->createView()


        ]);
    }
}