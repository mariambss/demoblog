<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{

    // chaque méthode du controller est associé a une route bien specifique 
    // Lorsque nous envoyons la route '/blog' dadns l'URL du navigateur ? cela execute automatiquement dans le controller, la méthode associé à celle-ci.
    // chaque methode renvoie un template sur le navigateur en fonction de la route transmise
    

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo): Response
    {
        //
        //
        //$repo = $this->getDoctrine()->getRepository(Article::class);
        //dump($repo);

        // findAll()
        $articles = $repo->findAll();
        dump($articles);

        return $this->render('blog/index.html.twig', [
            'articles' => $articles // Nous envoyons sur le template les articles selectionnés en BDD
        ]);
    }
    /**
     * @Route("/", name="home")
     */
    public function home(): Response
    {
        return $this->render('blog/home.html.twig',[
            'title' => 'bienvenue sur le blog Symfony',
            'age' => 25
        ]);
    }
    
        /**
         * @Route("/blog/new", name="blog_create")
         * @Route("/blog/{id}/edit", name="blog_edit")
         */
        public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
        {
            
            // if($request->request->count() > 0)
            // {
            //     $article = new Article;
            //     $article->setTitle($request->request->get('title'))
            //             ->setContent($request->request->get('content'))
            //             ->setImage($request->request->get('image'))
            //             ->setCreatedAt(new \DateTime());

            //     $manager->persist($article);
            //     $manager->flush();

            //     return $this->redirectToRoute('blog_show',[
            //         'id' => $article->getId()
            //     ]);
            // }

            if(!$article)
            {
                $article = new Article;
            }
            

                // On observe quand remplissant l'objet Article via les setteurs, les getteurs renvoient les données de l'article
                // directement dans les champs du formulaire 

            // $article->setTitle('titre plus')
            //         ->setContent('Contenu plus');

            // $form = $this->createFormBuilder($article)
            //              ->add('title')

            //              ->add('content',TextType::class)

            //              ->add('image')
                             
            //              ->getForm();

            $form = $this->createForm(ArticleType::class, $article);

            $form->handleRequest($request);

            dump($request);

            if($form->isSubmitted() && $form->isValid())
            {

                if(!$article->getId())
                {
                    $article->setCreatedAt(new \DateTime());

                }



                

                $manager->persist($article);
                $manager->flush();

                return $this->redirectToRoute('blog_show', [
                                'id'=> $article->getId()
                ]);
            }

            return $this->render('blog/create.html.twig', [
                'formArticle' => $form->createView(),
                'editMode' =>$article->getId()!== null 
            ]);
                
        }


    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article): Response //9
    {
        //
        //$repo = $this->getDoctrine()->getRepository(Article::class);

        //$article = $repo->find($id);
        //dump($article);

        return $this->render('blog/show.html.twig',[
            'article' => $article // 
        ]);
            
    }

        /**
         * 
         */


        /**
         * @Route("/blog/new", name="blog_create")
         */
        


}
