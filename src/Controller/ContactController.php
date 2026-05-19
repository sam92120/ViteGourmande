<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Contact;
use App\Entity\User;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;



final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
public function contact(
    Request $request,
    EntityManagerInterface $entityManager
): Response {

    if ($request->isMethod('POST')) {

        $contact = new Contact();

        $contact->setNom($request->request->get('nom'));
        $contact->setEmail($request->request->get('email'));
        $contact->setSujet($request->request->get('sujet'));
        $contact->setMessage($request->request->get('message'));

        $entityManager->persist($contact);
        $entityManager->flush();

        $this->addFlash('success', 'Message envoyé avec succès.');

        return $this->redirectToRoute('app_contact');
    }

    return $this->render('contact/index.html.twig');
}
   
}
