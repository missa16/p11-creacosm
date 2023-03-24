<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230322193830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_sondage_result ADD sonde_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_sondage_result ADD CONSTRAINT FK_CDC4B1D5F1B7D1A2 FOREIGN KEY (sonde_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CDC4B1D5F1B7D1A2 ON user_sondage_result (sonde_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_sondage_result DROP CONSTRAINT FK_CDC4B1D5F1B7D1A2');
        $this->addSql('DROP INDEX IDX_CDC4B1D5F1B7D1A2');
        $this->addSql('ALTER TABLE user_sondage_result DROP sonde_id');
    }
}
