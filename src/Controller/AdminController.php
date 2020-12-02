<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }
    /**
     * @Route("/admin/articles", name="admin_articles")
     */
    public function adminArticles(EntityManagerInterface $manager, ArticleRepository $repo): Response 
    {

        $colonnes = $manager->getClassMetadata(Article::class)->getFieldNames();

        dump($colonnes);

        $articles = $repo->findAll();

        dump($articles);


        return $this->render('admin/admin_articles.html.twig',[
                'colonnes' => $colonnes ,
                'articles' => $articles
        ]);
        
    }
    /**
     * @Route("/admin/article/new", name="admin_new_article")
     * @Route("/admin/{id}/edit-article/new", name="admin_edit_article")
     */
    public function adminForm(Request $request, EntityManagerInterface $manager, Article $article = null): Response
    {
        
        if(!$article)
        {
            $article = new Article;  
        }
       

        $formArticle =$this->createForm(ArticleType::class, $article);

        dump($request);

        $formArticle->handleRequest($request);

        if($formArticle->isSubmitted() && $formArticle->isValid())
        {

            if(!$article->getId())
            {
                $article->setCreatedAt(new \DateTime);
            }
            

            $manager->persist($article);
            $manager->flush();

            $this->addFlash('success',"l'article a bien été enregistré");
            return $this->redirectToRoute('admin_articles');

        }
        return $this->render('admin/admin_create.html.twig',[
            'formArticle' => $formArticle->createView()

        ]);
    }
    


}



