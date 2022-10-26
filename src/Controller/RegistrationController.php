<?php
namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    #[Route('/api/register', name: 'app_register')]
    public function index(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher): Response
    {
        // ... e.g. get the user data from a registration form
        $entityManager = $doctrine->getManager();
        $content = $request->getContent();
        var_dump($content);
        $user = new Participant();

        
        // TODO with content
        $user->setEmail('test@test.fr')
        ->setRoles([])
        ->setNom('testNom')
        ->setPrenom('TestPrenom')
        ->setTelephone('0123456789')
        ->setActif(false)
        ->setAdministrateur(false)
        ;

        $plaintextPassword = "testmdp";


        // hash the password (based on the security.yaml config for the $user class)
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(['user_id' => $user->getId()],
            Response::HTTP_OK,
        );
    }
}