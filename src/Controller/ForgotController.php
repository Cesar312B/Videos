<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\ChangePasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ForgotController extends  AbstractController 
{
    /**
     * @Route("/forgot", name="forgot")
     * @IsGranted("IS_AUTHENTICATED_FULLY",message="No tiene acceso a esta pagina")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userInfo = ['password' => null];
        $form = $this->createForm(ChangePasswordType::class, $userInfo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userInfo = $form->getData();
            $username = $this->getUser();
            $plainPassword = $userInfo['password'];

            
            $password = $encoder->encodePassword($username, $plainPassword);

            $username->setPassword($password);
            $entityManager->flush();
            
 
            return $this->redirectToRoute('video_index');
               
        }

        return $this->render('security/forgot.html.twig', array('form' => $form->createView()));
    }

      
    
}
