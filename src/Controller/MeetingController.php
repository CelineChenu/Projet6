<?php


namespace App\Controller;

use App\Entity\Event;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function show(Event $event)
    {
        return $this->render('home/eventpage.html.twig', ['event' =>$event]);
    }
}