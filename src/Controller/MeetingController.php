<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MeetingController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        return $this->render('home/index.html.twig', ['events' => $events]);
    }


    /**
     * @Route("eventpage/{id}", name="eventpage")
     * @param Event $event
     * @param User $user
     * @return Response
     */
    public function show(Event $event, User $user)
    {

        return $this->render('home/eventpage.html.twig', ['event' =>$event,'user' =>$user]);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @Route("registration/{eventId}/{userId}", name="addUserToEvent")
     */
    public function addUserToEvent(Request $request, EntityManagerInterface $manager)
    {
        $eventId = $request->get('eventId');
        $userId = $request->get('userId');
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        $event->addUser($user);

        $manager->persist($event);
        $manager->flush();

        return $this->redirectToRoute('eventpage', ['id' =>$eventId]);
    }
}