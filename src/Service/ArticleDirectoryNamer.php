<?php

namespace App\Service;

use App\Entity\ArticleImage;
use Vich\UploaderBundle\Mapping\PropertyMapping;
use Vich\UploaderBundle\Naming\DirectoryNamerInterface;

class ArticleDirectoryNamer implements DirectoryNamerInterface
{
    /**
     * @param ArticleImage|array $object
     */
    public function directoryName(object|array $object, PropertyMapping $mapping): string
    {
        if ($object->getArticle()) {
            if ($object->getArticle()->getId()) {

                return self::slugify($object->getArticle()->getId() . '-' . $object->getArticle()->getTitre());
            }
        }

        return 'articles';
    }

    private static function slugify(string $text): string
    { // replace non letter or digits by divider
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate divider
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = mb_strtolower($text);

        return $text ?: 'default-img';
    }
}
