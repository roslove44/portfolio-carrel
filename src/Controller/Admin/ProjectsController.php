<?php

namespace App\Controller\Admin;

use App\Entity\Projects;
use App\Form\ProjectType;
use App\Service\PicturesServices;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class ProjectsController extends AbstractController
{
    #[Route('/admin/projects', name: 'app_admin_projects')]
    public function addProject(Request $request, PicturesServices $picturesService): Response
    {
        $project = new Projects();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $proj_image = $form->get('proj_image')->getData();
            $picturesService->add($proj_image, width: 800, height: 533);
            dd($proj_image);
        }

        return $this->render('admin/projects.html.twig', [
            'addProject' => $form->createView(),
        ]);
    }
}
