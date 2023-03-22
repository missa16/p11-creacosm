<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320091457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE stats_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE stats_question (id INT NOT NULL, question_id INT NOT NULL, nom_stat VARCHAR(255) NOT NULL, json_stats JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A3B4DE461E27F6BF ON stats_question (question_id)');
        $this->addSql('ALTER TABLE stats_question ADD CONSTRAINT FK_A3B4DE461E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE stats_question_id_seq CASCADE');
        $this->addSql('ALTER TABLE stats_question DROP CONSTRAINT FK_A3B4DE461E27F6BF');
        $this->addSql('DROP TABLE stats_question');
    }
}
