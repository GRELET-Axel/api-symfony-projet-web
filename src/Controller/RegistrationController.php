<?php
namespace App\Controller;

use App\Entity\Participant;
use App\Entity\User;
use App\Repository\CampusRepository;
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
    public function index(ManagerRegistry $doctrine,Request $request,UserPasswordHasherInterface $passwordHasher, CampusRepository $campusRepository): Response
    {
        // ... e.g. get the user data from a registration form
        $entityManager = $doctrine->getManager();
        $content = json_decode($request->getContent());
        $user = new Participant();

        // $campus = $doctrine->getRepository(CampusRepository::class)->find($content->campus_id);
        $campus = $campusRepository->find($content->campus_id);
        $user->setEmail($content->email)
        ->setRoles($content->administrateur?['ROLE_ADMIN']:['ROLE_USER'])
        ->setNom($content->nom)
        ->setPrenom($content->prenom)
        ->setTelephone($content->telephone)
        ->setActif(false)
        ->setAdministrateur($content->administrateur?true:false)
        ->setCampus($campus)
        ;

        $plaintextPassword = $content->password;


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