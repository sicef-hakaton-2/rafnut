<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;

use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\Debug\ErrorHandler;

class PublicController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {         
        return $this->redirect($this->generateUrl('updateStatus'));
    }
    /**
     * @Route("/post-login", name="logged_in")
     */
    public function loggedInAction(Request $request)
    {
        return $this->redirect($this->generateUrl("app_home"));
    }
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'AppBundle:public:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }

    /**
     * @Route("/register", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createUserForm($user);
        $form->handleRequest($request); 

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($user);
            $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($password);
            try {
                $femaleImg = "data:image/png;base64,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";
                $maleImg = "data:image/png;base64,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";
                if($user->getGender()  == "male")
                    $user->setPicture($maleImg);
                else
                    $user->setPicture($femaleImg);
                $em->persist($user);
                $em->flush();
            } catch (\Doctrine\DBAL\DBALException $e) {
                return new JsonResponse("Registracija nije uspela");
                                
            }
            return $this->redirect($this->generateUrl('app_home'));        
        }

        return $this->render('AppBundle:public:register.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    private function createUserForm(User $user)
    {
        $form = $this->createForm(new UserType(), $user, array(
            'action' => $this->generateUrl('register'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create new user'));

        return $form;
    }
}
