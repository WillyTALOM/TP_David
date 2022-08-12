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

    #[Route('/admin/category/update/{id}', name: 'category_update')]
    public function update(Category $category,CategoryRepository $categoryRepository, Request $request, ManagerRegistry $managerRegistry): Response
    {
        $form = $this->createForm(CategoryType::class,
        $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $categories =  $categoryRepository->findAll();
            $categoryName = [];

            foreach ($categories as $categorie){

                $categoryName[] = $categorie->getName();
            }

            $slugger = new AsciiSlugger();
            $categorie->setSlug(strtolower($slugger->slug($form['name']->getData())));

            $manager = $managerRegistry->getManager();
            $manager->persist($category);
            $manager->flush();
        }

        return $this->render('category/form.html.twig', [
            'categoryForm' => $form->createView()
        ]);
    }

    #[Route('/admin/category/delete/{id}', name: 'category_delete')]
    public function delete(Category $category, ManagerRegistry $managerRegistry): Response
    {
        $manager = $managerRegistry->getManager();
        $manager->remove($category);
        $manager->flush();
        return $this->redirectToRoute('admin_category');

    }

}
