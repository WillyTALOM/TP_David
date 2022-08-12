<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    #[Route('/admin/category', name: 'admin_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll(); 
        return $this->render('category/categoryAdmin.html.twig', [
            'categories' =>  $categories,
        ]);
    }

    #[Route('/admin/category/create', name: 'create_category')]
    public function create(Request $request, CategoryRepository $categoryRepository, ManagerRegistry $managerRegistry): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isvalid())
        {
            $categories = $categoryRepository->findAll();
            $categoryNames = [];

            foreach ($categories as $categorie){
                $categoryNames [] = $categorie->getName();
            }

            $slugger = new AsciiSlugger();
            $category->setSlug(strtolower($slugger->slug($form['name']->getData())));

            

            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();

        }

        return $this->render('category/form.html.twig', [
            'categoryForm' => $form->createView()
        ]);

    }
}
