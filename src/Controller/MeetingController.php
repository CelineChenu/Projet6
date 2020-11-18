<?php


namespace App\Controller;

use App\Entity\Chat;
use App\Entity\Event;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MeetingController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="homepage")
     * @param EntityManagerInterface $manager
     */
    public function index(EntityManagerInterface $manager)
    {
        //$events = $this->getDoctrine()->getRepository(Event::class)->findAll();
        $events = $this->getDoctrine()->getRepository(Event::class)->findLastFive();
        return $this->render('home/index.html.twig', ['events' => $events]);
    }


    /**
     * @Route("eventpage/{id}", name="eventpage")
     * @param Request $request
     * @param Event $event
     * @return Response
     */
    public function show(Event $event, Request $request)
    {
        $user = $this->security->getUser();

        $chats = $this->getDoctrine()->getRepository(Chat::class)->findAll();
        return $this->render('home/eventpage.html.twig', ['event' =>$event,'user' =>$user,'chats'=>$chats]);
    }

    /**
     * @Route("addChat", name="addChat")
     * @param Request $request
     * @return Response
     */
    public function addChat(Request $request, EntityManagerInterface $manager)
    {
        $user = $this->security->getUser();
        $event = $this->getDoctrine()->getRepository(Event::class)->find($request->get("eventId"));
        $message = $request->get("message");
        //enregistrer le message en base avec le user
        $chat = new Chat();
        $chat->setUser($user);
        $chat->setEvent($event);
        $chat->setContent($message);
        $chat->setCreatedAt(new \DateTime(date("Y-m-d H:i:s")));
        $manager->persist($chat);
        $manager->flush();
        $chats = $this->getDoctrine()->getRepository(Chat::class)->findAll();
        return $this->render('home/chat.html.twig', ['chats'=>$chats]);
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

    /**
     * @Route("oldevents", name="oldevents")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function oldEvents(EntityManagerInterface $manager)
    {
        $user = $this->security->getUser();
        $events = $this->getDoctrine()->getRepository(Event::class)->findOld($user);

        return $this->render('oldevents.html.twig', ['events' => $events,'user' =>$user]);
    }

    /**
     * @Route("futureevents", name="futureevents")
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function futureEvents(EntityManagerInterface $manager)
    {
        $user = $this->security->getUser();
        $events = $this->getDoctrine()->getRepository(Event::class)->findFuture($user);

        return $this->render('futureevents.html.twig', ['events' => $events,'user' =>$user]);
    }
}