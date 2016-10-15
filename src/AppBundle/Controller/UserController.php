<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

use AppBundle\Entity\User;
use AppBundle\Form\EditUserType;

/**
 * @Route("/app", name="app")
 */
class UserController extends Controller
{
    private function serialize($object)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $jsonContent = $serializer->serialize($object, 'json');
        return $jsonContent;
    }

    /**
     * @Route("/", name="app_home")
     */
    public function indexAction(Request $request)
    {
        return $this->redirect($this->generateUrl('updateStatus'));
    }

    /**
     * @Route("/update-status", name="updateStatus")
     */
    public function updateStatusAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:Status');
        $qb   = $repo->createQueryBuilder('s');

        $qb->andWhere('s.user = :id');
        $qb->setParameter('id', $this->getUser());

        $statuses = $qb->getQuery()->getResult();
        return $this->render('AppBundle:user:update-status.html.twig', array(
            "statuses" => $statuses,
        ));
    }

    /**
     * @Route("/news", name="news")
     */
    public function newsAction(Request $request)
    {
        $news = $self->findNews($this->getUser()->getStatuses()->last()->getLocation());
        return $this->render('AppBundle:user:news.html.twig', array(
        ));
    }

    /**
     * @Route("/important-info", name="importantInfo")
     */
    public function importantInfoAction(Request $request)
    {
        return $this->render('AppBundle:user:important-info.html.twig', array(
            'lastStatus' => $this->getUser()->getStatuses()->last()
        ));
    }

    /**
     * @Route("/profile/{id}", requirements={"id" = "\d+"}, name="profile")
     */
    public function profileAction(Request $request, $id = -1)
    {
        $repository = $this->getDoctrine()->getRepository('AppBundle:User');
        $user = $repository->findOneById($id);

        if (!($user)) $user=$this->getUser();

        return $this->render('AppBundle:user:profile.html.twig', array(
            'user' => $user,
            'subscribed' => $this->getUser()->getPeopleIFollow()->contains($user)
        ));
    }

    /**
     * @Route("/list-people", name="listPeople")
     */
    public function listPeopleAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $allUsers = $repo->findAll();

        $user = $this->getUser();

        $string = $request->query->get('q');

        if (!($string)){
            return $this->render('AppBundle:user:list-people.html.twig', array('users'=>$allUsers, 'query'=>''));
        }

        $parts = explode(" ", $string);
        $max = count($parts);
        $results = [];
        if ($max>0){
            //ako je samo jedan pojam unesen, mora biti tacan
            if ($max===1){
                foreach ($allUsers as $users){
                    $fullString = $users->getUsername() . $users->getEmail() . $users->getNumber() . $users->getFullName() . $users->getGender();
                    if (stripos($fullString, $parts[0]) !== false){
                        if ($user->getId() === $users->getId()) continue;
                        $results[] = $users;
                    }
                }
            }

            //ako je vise od jednog, jedan moze da nije tacno unesen (recimo)
            if ($max>1){
                foreach ($allUsers as $users){
                    $c = 0;
                    $fullString = $users->getUsername() . $users->getEmail() . $users->getNumber() . $users->getFullName() . $users->getGender();
                    foreach ($parts as $part){
                        if (stripos($fullString, $part) !== false){
                            $c++;
                        }
                    }
                    if ($max-$c<2){
                        if ($user->getId() === $users->getId()) continue;
                        $results[] = $users;
                    }
                }
            }
        }
        //var_dump($results);

        return $this->render('AppBundle:user:list-people.html.twig', array('users'=>$results, 'query'=>$string
        ));
    }

    /**
     * @Route("/following", name="peopleIFollow")
     */
    public function peopleFollowAction(Request $request)
    {
        $users = $this->getUser()->getPeopleIFollow();

        return $this->render('AppBundle:user:people-i-follow.html.twig', array(
            'users'   => $users,
            ));
    }

    /**
     * @Route("/profile-change", name="profileChange")
     */
    public function profileChangeAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createUserForm($user);
        $form->handleRequest($request);

        if ($form->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('profileChange'));
        }

        return $this->render('AppBundle:user:edit.html.twig', array(
            'form'   => $form->createView(),
        ));
    }

    private function createUserForm(User $user)
    {
        $form = $this->createForm(new EditUserType(), $user, array(
            'action' => $this->generateUrl('profileChange'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update my changes'));

        return $form;
    }
    /**
     * @Route("/wow", name="wow")
     */
    public function wowAction(Request $request)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('AppBundle:User');
        $users = $repo->findAll();
        
        return $this->render('AppBundle:user:wow.html.twig', array(
            'users'   => $users,
            ));
    }

    public function findNews($name){

    }
}
