<?php

namespace App\Fixtures\Providers;

use App\Entity\ArticleImage;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleProvider
{
    public function __construct(
        private Generator $faker
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    public function generateArticleContent(): string
    {
        return file_get_contents('https://loripsum.net/api/10/long/headers/link/ul/dl');
    }

    public function generateArticleDate(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear());
    }

    public function uploadArticleImage(): ArticleImage
    {
        $files = glob(realpath(\dirname(__DIR__) . '/Images/Articles') . '/*.*');

        $file = new File($files[array_rand($files)]);
        $uploadFile = new UploadedFile($file, $file->getBaseName());

        return (new ArticleImage())
            ->setImage($uploadFile);
    }
}
