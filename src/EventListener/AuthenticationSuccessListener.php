<?php

namespace App\EventListener;

use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Repository\RepositoryFactory;

class AuthenticationSuccessListener{

/**
 * @param AuthenticationSuccessEvent $event
*/
    function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $event->setData([
            'code' => $event->getResponse()->getStatusCode(),
            key($event->getData()) => $event->getData()['token'],
            'user_id' => $event->getUser()->getUserIdentifier()
        ]);
    }

}
