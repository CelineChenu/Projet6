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

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

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

    public function searchBar()
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('handle_search'))
            ->add("query", TextType::class, [
                'attr' => [
                    'placeholder'   => 'Entrez votre recherche...'
                ]
            ])
            ->getForm()
        ;

        return $this->render('search/search.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/handleSearch/{_query?}", name="handle_search", methods={"POST", "GET"})
     * @param Request $request
     * @param $_query
     * @return JsonResponse
     */
    public function handleSearchRequest(Request $request, $_query)
    {
        $em = $this->getDoctrine()->getManager();

        if ($_query)
        {
            $data = $em->getRepository(Restaurant::class)->findByName($_query);
        } else {
            $data = $em->getRepository(Restaurant::class)->findAll();
        }
        foreach($data as $d){
            $dataArray[] = $d->toArray();
        }


        return new JsonResponse($dataArray);
    }


    /**
     * @Route("/restaurant/{id?}", name="restaurant_page", methods={"GET"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function restaurantSingle(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $restaurant= null;

        if ($id) {
            $restaurant = $em->getRepository(Restaurant::class)->findOneBy(['id' => $id]);
        }

        return $this->render('home/restaurant.html.twig', [
            'restaurant' => $restaurant
        ]);
    }
}