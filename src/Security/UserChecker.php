<?php
namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
class UserChecker implements UserCheckerInterface
{

  
    public function checkPreAuth(UserInterface $user): void
    {

     
     
        if (!$user instanceof AppUser ) {
            return;
        }

        if ($user->getIsActive() == false) {
          
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Tu cuenta esta bloqueada.');
        }

        
        if ($user->isVerified() == false) {
          
            // the message passed to this exception is meant to be displayed to the user
            throw new CustomUserMessageAccountStatusException('Tu cuenta no esta activada, revisa tu correo electronico.');
        }

        

   
    }



    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }

    
        if ($user->getIsActive() == false ) {
        
        }


    }


   
}