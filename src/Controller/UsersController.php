<?php

namespace App\Controller;

use App\Entity\Prestations;
use App\Entity\Users;
use App\Form\AddPrestationType;
use App\Form\AddUserInfosType;
use App\Repository\AppointmentsRepository;
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
    public function infosUser($id, Users $user, EntityManagerInterface $manager, Request $request){


        if($id === strval($this->getUser()->getId())){

            $form = $this->createForm(AddUserInfosType::class, $user);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){             
                $manager->persist($user);
                $manager->flush();
                return $this->redirectToRoute('home');
            }
            return $this->render("users/infos.html.twig", [
                'user' => $form->createView(),
                'test' => 'testtest'.$id
            ]);

        }else{

            return $this->render("users/infos.html.twig", [
                'test' => 'error'
            ]);

        }
                
    }
    /**
     * @Route("/Agenda/{id}", name="agenda")
     */
    public function Agenda($id, AppointmentsRepository $appointmentsRepository){


        if($id === strval($this->getUser()->getId())){

            return $this->render("users/agenda.html.twig", [
                'user' => $id,
            ]);

        }else{

            return $this->render("users/agenda.html.twig", [
                'test' => 'error'
            ]);

        }

    }


    /**
     * @Route("/prestations/{id}", name="prestations")
     */
    public function Prestations($id, Users $user, EntityManagerInterface $manager, Request $request){

         if($id === strval($this->getUser()->getId())){
             
            $presta = new Prestations();
            $form = $this->createForm(AddPrestationType::class, $presta);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){  

                $presta->setUsers($user);           
                $manager->persist($presta);
                $manager->flush();
                return $this->redirectToRoute('prestations', [
                    'id' => $id
                ]);

            }

            return $this->render("users/prestations.html.twig", [
                'addPresta' => $form->createView(),
                'presta' =>  $user->getPrestation()
            ]);

        }else{

            return $this->render("users/prestations.html.twig", [
                'test' => 'error'
            ]);

        }
    }
}
