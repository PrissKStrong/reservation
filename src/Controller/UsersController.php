<?php

namespace App\Controller;

use App\Entity\Users;
use App\Repository\UsersRepository;
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
    public function infosUser($id){


        if($id === strval($this->getUser()->getId())){

            return $this->render("users/infos.html.twig", [
                'test' => 'testtest'.$id,
            ]);

        }else{

            return $this->render("users/infos.html.twig", [
                'test' => 'error'
            ]);

        }

    }
    
}
