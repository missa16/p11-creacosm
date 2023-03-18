<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317090630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "user" DROP nom');
        $this->addSql('ALTER TABLE "user" DROP prenom');
        $this->addSql('ALTER TABLE "user" DROP date_naissance');
        $this->addSql('ALTER TABLE "user" DROP formation');
        $this->addSql('ALTER TABLE "user" DROP ville');
        $this->addSql('ALTER TABLE "user" DROP genre');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" ADD nom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD prenom VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD date_naissance DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD formation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD ville VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD genre VARCHAR(255) DEFAULT NULL');
    }
}
