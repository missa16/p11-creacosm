<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230317091139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sondage DROP CONSTRAINT fk_7579c89f73a201e5');
        $this->addSql('DROP INDEX idx_7579c89f73a201e5');
        $this->addSql('ALTER TABLE sondage RENAME COLUMN createur_id TO sondeur_id');
        $this->addSql('ALTER TABLE sondage ADD CONSTRAINT FK_7579C89FF68B4784 FOREIGN KEY (sondeur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7579C89FF68B4784 ON sondage (sondeur_id)');
        $this->addSql('ALTER TABLE "user" ADD nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD prenom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD date_naissance TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD ville VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "user" ADD genre VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE "user" ADD formation VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE sondage DROP CONSTRAINT FK_7579C89FF68B4784');
        $this->addSql('DROP INDEX IDX_7579C89FF68B4784');
        $this->addSql('ALTER TABLE sondage RENAME COLUMN sondeur_id TO createur_id');
        $this->addSql('ALTER TABLE sondage ADD CONSTRAINT fk_7579c89f73a201e5 FOREIGN KEY (createur_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_7579c89f73a201e5 ON sondage (createur_id)');
        $this->addSql('ALTER TABLE "user" DROP nom');
        $this->addSql('ALTER TABLE "user" DROP prenom');
        $this->addSql('ALTER TABLE "user" DROP date_naissance');
        $this->addSql('ALTER TABLE "user" DROP ville');
        $this->addSql('ALTER TABLE "user" DROP genre');
        $this->addSql('ALTER TABLE "user" DROP formation');
    }
}
