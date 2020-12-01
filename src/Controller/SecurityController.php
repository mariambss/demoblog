<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder, AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted('ROLE_USER')) 
        {
            return $this->redirectToRoute('blog');
        }

        $user = new User; 

        dump($request);

        $formRegistration = $this->createForm(RegistrationType::class, $user);

        $formRegistration->handleRequest($request);

        if($formRegistration->isSubmitted() && $formRegistration->isValid())
        {
            // encodePassword() est une methode issue de l'interface UserPasswordEencodeerInterface permettant d'encoder le code
            
            // 5 methodes a implenter pour encoder le mot de passe : getPassword(), getUsername(), getSalt(), getRoles(), eraseCredentials()
            $hash = $encoder->encodePassword($user, $user->getPassword());

            $user->setPassword($hash);

            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('security_login');
        }

        return $this->render('security/registration.html.twig',[
            'formRegistration' => $formRegistration->createView()
        ]);


    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, AuthorizationCheckerInterface $authChecker): Response
    {
        // si l'internaute est co
        if ($authChecker->isGranted('ROLE_USER')) 
        {
            return $this->redirectToRoute('blog');
        }
        // recuperation du messaged'erreur en cas de mauvaise connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        // recuperation du dernier username (email) saisi par l'internaute en cas d'erreur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig',[
            'error' => $error,
            'last_username'=> $lastUsername
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        // cette methode ne return rien , il nous suffit d'avoir une route pour se deconnecter

    }


}
