<?php

namespace App\Controller\Admin;

use App\Entity\Tags;
use App\Form\TagFormType;
use App\Repository\CategoriesRepository;
use App\Repository\TagsRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
class TagsController extends AbstractController
{
    #[Route('admin/tags/', name: 'app_tags')]
    public function Tags(TagsRepository $tagsRepository, CategoriesRepository $categoriesRepository, Request $request, EntityManagerInterface $em): Response
    {
        $tag = new Tags;
        $form = $this->createForm(TagFormType::class, $tag);
        $form->handleRequest($request);
        $showForm = false;

        if ($form->isSubmitted()) {
            if (!$form->isValid()) {
                $showForm = true;
            } else {
                $tag->setName(strtolower($tag->getName()));
                $em->persist($tag);
                $em->flush();

                $this->addFlash("success", "Tag ajouté avec succès");
                return $this->redirectToRoute('app_tags');
            }
        }

        $tagForm = $form->createView();
        $categories = $categoriesRepository->findBy([], ['id' => 'ASC']);
        $tags = $tagsRepository->findBy([], ['id' => 'ASC']);

        return $this->render('admin/tags.html.twig', compact('tags', 'tagForm', 'showForm', 'categories'));
    }

    #[Route('admin/tags/add/ajax/{label}', name: 'app_admin_tags', methods: ["POST"])]
    public function addTagsAjaxSelect2($label, EntityManagerInterface $em): JsonResponse
    {
        try {
            $tag = new Tags();
            $tag->setName(trim(strip_tags($label)));
            $em->persist($tag);
            $em->flush();
            $id = $tag->getId();
            $exist = false;
            return new JsonResponse(compact('id', 'exist'));
        } catch (UniqueConstraintViolationException $e) {
            // La contrainte d'unicité n'est pas respectée, l'élément existe déjà.
            // Vous pouvez rechercher l'élément existant par sa valeur (nom) dans la base de données.
            $existingTag = $em->getRepository(Tags::class)->findOneBy(['name' => $tag->getName()]);

            if ($existingTag) {
                $id = $existingTag->getId();
                $exist = true;
                return new JsonResponse(compact('id', 'exist'));
            }

            // Si l'élément n'existe pas, vous pouvez gérer la situation comme bon vous semble.
            // Par exemple, renvoyer une réponse JSON indiquant que l'élément n'existe pas.
            return new JsonResponse(['error' => 'L\'élément n\'existe pas'], 400);
        }
    }

    #[Route('admin/tags/delete/{id}', name: 'app_delete_tags', methods: ['DELETE'])]
    public function deleteTags(Tags $tag, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $data['_token'])) {
            // Le token csrf est valide
            $em->remove($tag);
            $em->flush();
            return new JsonResponse(['success' => true], 200);
        }

        return new JsonResponse(['error' => 'Token Invalide !']);
    }

    #[Route('admin/tags/edit/{id}', name: 'app_edit_tags', methods: ['PUT'])]
    public function editTags(Tags $tag, EntityManagerInterface $em, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if ($this->isCsrfTokenValid('update' . $tag->getId(), $data['_token'])) {
            // Le token csrf est valide
            $oldName = $tag->getName();
            if ($oldName !== $data['_updatedName']) {
                $tag->setName($data['_updatedName']);
                $em->persist($tag);
                $em->flush();
                return new JsonResponse(['success' => true], 200);
            }
            return new JsonResponse(['error' => 'Erreur de modification !']);
        }

        return new JsonResponse(['error' => 'Token Invalide !']);
    }
}
