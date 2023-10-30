<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Projects;
use App\Form\ProjectType;
use App\Service\PicturesServices;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

#[IsGranted("ROLE_ADMIN")]
class ProjectsController extends AbstractController
{
    #[Route('/admin/projects', name: 'app_admin_projects')]
    public function addProject(Request $request, PicturesServices $picturesService, SluggerInterface $slugger, EntityManagerInterface $em): Response
    {
        $project = new Projects();
        $form = $this->createForm(ProjectType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Upload images du carrousel
            // On récupère les images 
            $images = $form->get('images')->getData();
            foreach ($images as $image) {
                //    On définit le dossier de destination 
                $folder = "projects";
                // On appelle le service d'ajout 
                $fichier = $picturesService->add(
                    $image,
                    $folder,
                    300,
                    300
                );
                $img = new Images();
                $img->setName(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME));
                $img->setFileSrc($fichier);

                $project->addImage($img);
            }
            // Génération de slug
            $slug = $slugger->slug($project->getClient())->lower();

            // Project update
            // Upload image de présentation
            $proj_image = $form->get('proj_image')->getData();
            $proj_image = $picturesService->add($proj_image, 'thumbnails', width: 400, height: 266.5);
            $project->setSlug($slug);
            $project->setProjImage($proj_image);

            $em->persist($project);
            $em->flush();

            $name = $project->getClient();

            $this->addFlash('success', "Projet $name enregistré avec succès!");
            return $this->redirectToRoute('app_projects_details', ['slug' => $project->getSlug()]);
        }

        return $this->render('admin/projects.html.twig', [
            'addProject' => $form->createView(),
        ]);
    }
}
