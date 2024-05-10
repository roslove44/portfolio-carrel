<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AllProjectsController extends AbstractController
{
    #[Route('/projets', name: 'app_projects')]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        $projects = $projectsRepository->findBy([], ['priority' => 'ASC']);
        return $this->render('projects/index.html.twig', compact('projects'));
    }

    #[Route('/projets/{slug}', name: 'app_projects_details')]
    public function bySlug(Projects $project): Response
    {
        return $this->render('projects/bySlug.html.twig', compact('project'));
    }
}
