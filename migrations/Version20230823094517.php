<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823094517 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_beneficiaire (article_id INT NOT NULL, beneficiaire_id INT NOT NULL, INDEX IDX_620CAF937294869C (article_id), INDEX IDX_620CAF935AF81F68 (beneficiaire_id), PRIMARY KEY(article_id, beneficiaire_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_user (article_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_3DD151487294869C (article_id), INDEX IDX_3DD15148A76ED395 (user_id), PRIMARY KEY(article_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_beneficiaire ADD CONSTRAINT FK_620CAF937294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_beneficiaire ADD CONSTRAINT FK_620CAF935AF81F68 FOREIGN KEY (beneficiaire_id) REFERENCES beneficiaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_user ADD CONSTRAINT FK_3DD151487294869C FOREIGN KEY (article_id) REFERENCES article (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_user ADD CONSTRAINT FK_3DD15148A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_beneficiaire DROP FOREIGN KEY FK_620CAF937294869C');
        $this->addSql('ALTER TABLE article_beneficiaire DROP FOREIGN KEY FK_620CAF935AF81F68');
        $this->addSql('ALTER TABLE article_user DROP FOREIGN KEY FK_3DD151487294869C');
        $this->addSql('ALTER TABLE article_user DROP FOREIGN KEY FK_3DD15148A76ED395');
        $this->addSql('DROP TABLE article_beneficiaire');
        $this->addSql('DROP TABLE article_user');
    }
}
