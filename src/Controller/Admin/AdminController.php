<?php

namespace App\Controller\Admin;

use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        $projects = $projectsRepository->findAll();
        return $this->render('admin/index.html.twig', compact('projects'));
    }
}
