<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ContactTypeFormType;
use App\Service\SendMailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllProjectsController extends AbstractController
{
    #[Route('/projects', name: 'app_projects')]
    public function index(): Response
    {
        return $this->render('projects/index.html.twig');
    }

    #[Route('/projects/{slug}', name: 'app_projects_details')]
    public function bySlug(Projects $project): Response
    {
        return $this->render('projects/bySlug.html.twig', compact('project'));
    }
}
