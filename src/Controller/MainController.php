<?php

namespace App\Controller;

use App\Form\ContactTypeFormType;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request, SendMailService $mailer): Response
    {
        $contactForm = $this->createForm(ContactTypeFormType::class);
        $contactForm->handleRequest($request);
        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $contact = $contactForm->getData();

            $mailer->send(
                $this->getParameter('company_email_sender'),
                $this->getParameter('company_email'),
                $contact['subject'],
                'contact',
                $contact,
                $contact['client_email']
            );
            $this->addFlash("success", "Merci pour votre message. Il est bien arrivé dans notre boîte de réception. Nous reviendrons vers vous sous peu.");
            return $this->redirectToRoute('app_main');
        }
        return $this->render('main/index.html.twig', [
            'contactForm' => $contactForm->createView(),
        ]);
    }
}
