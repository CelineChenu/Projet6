<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    /**
     * @Route("inscription", name="inscription")
     */
    public function index(Request $request, EntityManagerInterface $manager)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $request->getSession()->set('user', $user);
            return $this->redirectToRoute('login');
        }

        return $this->render('inscription.html.twig',['formUser' => $form->createView()]);

    }

}