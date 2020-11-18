<?php


namespace App\Controller;

use App\Entity\Event;
use App\Entity\Restaurant;
use App\Entity\User;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;

class RestaurantController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("listRestaurant", name="listRestaurant")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function list(Request $request, EntityManagerInterface $manager){

        $restaurants = $manager->getRepository(Restaurant::class)->findAll();


        return $this->render('restaurant/list.html.twig',['restaurants'=>$restaurants]);
    }

    /**
     * @Route("restaurant/{id}", name="restaurant")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param Restaurant $restaurant
     * @return Response
     */
    public function show(Restaurant $restaurant, Request $request,EntityManagerInterface $manager)
    {
        $event = new Event();
        $user = $this->security->getUser();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setRestaurant($restaurant);
            $event->setCreator($user);
            $manager->persist($event);
            $manager->flush();

            return $this->redirectToRoute('listRestaurant');
        }


        return $this->render('restaurant/restaurant.html.twig', ['restaurant' =>$restaurant,'formEvent' => $form->createView()]);
    }
}