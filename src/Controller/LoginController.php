<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;


class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
       
        $error = $authenticationUtils->getLastAuthenticationError();
        
        
        // last username entered by the user
       
        $lastUsername = $authenticationUtils->getLastUsername();


        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',             
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }
}
