<?php

namespace App\Fixtures\Providers;

use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;

class ArticleProvider

{
    public function __construct(

        private Generator $faker
    ) {

        $this->faker = Factory::create('fr_FR');
    }

    public function generateLoremDesc(): string
    {
        $content =
            file_get_contents('https://loripsum.net/api/10/long/headers/link/ul/dl');

        return $content;
    }

    public function generateDate(): DateTimeImmutable
    {
        $datetime = DateTimeImmutable::createFromMutable($this->faker->dateTimeThisYear());

        return $datetime;
    }
}
