<?php

namespace App\Fixtures\Providers;

class CategorieProvider
{
    /* Nous définissons notre méthode custom */
    public function randomTag(): string
    {
        /* On liste dans un tableau les choix de catégorie */
        $tagList = [
            'Php Object',
            'NodeJs',
            'Symfony',
            'Twig',
            'Api Platform',
            'JavaScript',
            'GitHub',
            'CI/CD',
            'Framework',
            'WebDesign',
        ];
        /* On retourne un choix aléatoire dans les valeurs du tableau */
        return $tagList[array_rand($tagList)];
    }
}
