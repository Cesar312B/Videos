<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Security;

class RequestListener
{

    private $security; // needed to get the user
    private $router; // needed to generate the logout-url
  

    public function __construct(Security $security, UrlGeneratorInterface $router ) 
    {
        $this->security = $security;
        $this->router = $router;


    }

    public function __invoke(RequestEvent $event) : void
    {
        $user = $this->security->getUser();

        // Don't do anything if no user is logged in.
        if ($user === null) {
            return;
        }

      
        if ($user->getIsActive() === false) {
            // Generate the logout url based on the path name and redirect to it.
            $url = $this->router->generate('app_logout');
            $event->setResponse(new RedirectResponse($url));
        }
    }
}