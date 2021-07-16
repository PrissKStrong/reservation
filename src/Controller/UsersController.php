<?php

namespace App\Controller;

use Exception;
use App\Entity\Users;
use App\Entity\Categories;
use App\Entity\Prestations;
use App\Form\AddCategoryType;
use App\Form\AddUserInfosType;
use App\Form\AddPrestationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AppointmentsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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

                $workingWeek = array();

                if ($request->get('lundiCheck') === "on") {
                    $lundi = array(
                        'daysOfWeek' => [1],
                        'startTime' => $request->get('lundiStart'),
                        'endTime' => $request->get('lundiEnd')
                    );
                    $workingWeek[] = $lundi;
                }

                if ($request->get('mardiCheck') === "on") {
                    $mardi = array(
                        'daysOfWeek' => [2],
                        'startTime' => $request->get('mardiStart'),
                        'endTime' => $request->get('mardiEnd')
                    );
                    $workingWeek[] = $mardi;
                }

                if ($request->get('mercrediCheck') === "on") {
                    $mercredi = array(
                        'daysOfWeek' => [3],
                        'startTime' => $request->get('mercrediStart'),
                        'endTime' => $request->get('mercrediEnd')
                    );
                    $workingWeek[] = $mercredi;
                }

                if ($request->get('jeudiCheck') === "on") {
                    $jeudi = array(
                        'daysOfWeek' => [4],
                        'startTime' => $request->get('jeudiStart'),
                        'endTime' => $request->get('jeudiEnd')
                    );
                    $workingWeek[] = $jeudi;
                }

                if ($request->get('vendrediCheck') === "on") {
                    $vendredi = array(
                        'daysOfWeek' => [5],
                        'startTime' => $request->get('vendrediStart'),
                        'endTime' => $request->get('vendrediEnd')
                    );
                    $workingWeek[] = $vendredi;
                }

                if ($request->get('samediCheck') === "on") {
                    $samedi = array(
                        'daysOfWeek' => [6],
                        'startTime' => $request->get('samediStart'),
                        'endTime' => $request->get('samediEnd')
                    );
                    $workingWeek[] = $samedi;
                }

                if ($request->get('dimancheCheck') === "on") {
                    $dimanche = array(
                        'daysOfWeek' => [7],
                        'startTime' => $request->get('dimancheStart'),
                        'endTime' => $request->get('dimancheEnd')
                    );
                    $workingWeek[] = $dimanche;
                }

                $user->setWorkDays($workingWeek);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success', "Vos informations sont à jour!");

                return $this->redirectToRoute('home');
            }
            return $this->render("users/infos.html.twig", [
                'user' => $form->createView(),
                'userId' => $id,
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
    public function Agenda($id, AppointmentsRepository $appointmentsRepository)
    {


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

                if ($request->files->get('add_prestation')['image']) {

                    $file = $request->files->get('add_prestation')['image'];
                    $directory = $this->getParameter('uploads_directory');
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    try {

                        $file->move(
                            $directory,
                            $fileName
                        );
                        $presta->setImage($fileName);
                    } catch (FileException $e) {

                        throw new Exception($e);
                    }
                } else {

                    throw new Exception('Bad request');
                }


                $presta->setUsers($user);

                $manager->persist($presta);
                $manager->flush();

                $this->addFlash('success', "La prestation a bien été ajoutée!");

                return $this->redirectToRoute('prestations', [
                    'id' => $id
                ]);
            }

            if ($form2->isSubmitted() && $form2->isValid()) {

                if ($request->files->get('add_category')['image']) {

                    $file = $request->files->get('add_category')['image'];

                    $directory = $this->getParameter('uploads_directory');
                    $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                    try {

                        $file->move(
                            $directory,
                            $fileName
                        );
                        $category->setImage($fileName);
                    } catch (FileException $e) {

                        throw new Exception($e);
                    }
                } else {

                    throw new Exception('Bad request');
                }

                $category->setUsers($user);

                $manager->persist($category);
                $manager->flush();

                $this->addFlash('success', "La catégorie a bien été ajoutée!");

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
    public function editCategory(Request $request, Categories $category, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AddCategoryType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            if ($request->files->get('add_category')['image']) {

                $file = $request->files->get('add_category')['image'];

                $directory = $this->getParameter('uploads_directory');
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {

                    $file->move(
                        $directory,
                        $fileName
                    );
                    $category->setImage($fileName);
                } catch (FileException $e) {

                    throw new Exception($e);
                }
            } else {

                throw new Exception('Bad request');
            }

            $manager->persist($category);
            $manager->flush();

            $this->addFlash('warning', "La catégorie a bien été modfiée!");

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
    public function editPrestation(Request $request, Prestations $presta, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(AddPrestationType::class, $presta);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();

            if ($request->files->get('add_prestation')['image']) {

                $file = $request->files->get('add_prestation')['image'];

                $directory = $this->getParameter('uploads_directory');
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {

                    $file->move(
                        $directory,
                        $fileName
                    );
                    $presta->setImage($fileName);
                } catch (FileException $e) {

                    throw new Exception($e);
                }
            } else {

                throw new Exception('Bad request');
            }

            $manager->persist($presta);
            $manager->flush();

            $this->addFlash('warning', "La <prestation></prestation> a bien été modfiée!");

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
