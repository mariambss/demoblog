<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /**
         * Les fixtureq permettent de créer des données fictives, des fausses données en bdd
         * nous  créons ici une boucle afin de créer 10 articles en bdd
         * pour pouvoir inserer des articles en bdd, nous devons passer pour l'entité Article qui reflete la table SQL
         */
        for($i = 1; $i<= 10; $i++)
        {
            $article = new Article;

            $article->setTitle("Titre de l'article n°$i")
                    ->setContent("<p>Contenu de l'article n°$i</p>")
                    ->setImage("http://picsum.photos/200/300")
                    ->setCreatedAt(new \ DateTime());

                    // ObjectManager permet de manipuler les lignes dans la bdd (insert, update, delete)
                    // peersist() : permet de preparer les requetes d'insertions
            $manager->persist($article);
        }
        // flush() : permet de liberer l'insertion en bdd
        $manager->flush();
    }
}
