<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230314150729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE test_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reponse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE sondage_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_sondage_reponse_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_sondage_result_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE question (id INT NOT NULL, type_question_id INT NOT NULL, sondage_id INT NOT NULL, intitule VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B6F7494E553E212E ON question (type_question_id)');
        $this->addSql('CREATE INDEX IDX_B6F7494EBAF4AE56 ON question (sondage_id)');
        $this->addSql('CREATE TABLE reponse (id INT NOT NULL, question_id INT NOT NULL, la_reponse VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5FB6DEC71E27F6BF ON reponse (question_id)');
        $this->addSql('CREATE TABLE sondage (id INT NOT NULL, intitule VARCHAR(255) NOT NULL, etat_sondage VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image_couverture VARCHAR(255) DEFAULT NULL, date_creation TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_lancement TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_fin TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, date_update TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE type_question (id INT NOT NULL, intitule_type VARCHAR(255) NOT NULL, is_multiple BOOLEAN NOT NULL, is_expanded BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE TABLE user_sondage_reponse (id INT NOT NULL, question_id INT NOT NULL, user_sondage_result_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2D6F17321E27F6BF ON user_sondage_reponse (question_id)');
        $this->addSql('CREATE INDEX IDX_2D6F17328CD4AB76 ON user_sondage_reponse (user_sondage_result_id)');
        $this->addSql('CREATE TABLE user_sondage_reponse_reponse (user_sondage_reponse_id INT NOT NULL, reponse_id INT NOT NULL, PRIMARY KEY(user_sondage_reponse_id, reponse_id))');
        $this->addSql('CREATE INDEX IDX_8E9C9FC099200CBC ON user_sondage_reponse_reponse (user_sondage_reponse_id)');
        $this->addSql('CREATE INDEX IDX_8E9C9FC0CF18BB82 ON user_sondage_reponse_reponse (reponse_id)');
        $this->addSql('CREATE TABLE user_sondage_result (id INT NOT NULL, sonde_id INT DEFAULT NULL, sondage_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CDC4B1D5F1B7D1A2 ON user_sondage_result (sonde_id)');
        $this->addSql('CREATE INDEX IDX_CDC4B1D5BAF4AE56 ON user_sondage_result (sondage_id)');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494E553E212E FOREIGN KEY (type_question_id) REFERENCES type_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EBAF4AE56 FOREIGN KEY (sondage_id) REFERENCES sondage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC71E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_reponse ADD CONSTRAINT FK_2D6F17321E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_reponse ADD CONSTRAINT FK_2D6F17328CD4AB76 FOREIGN KEY (user_sondage_result_id) REFERENCES user_sondage_result (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_reponse_reponse ADD CONSTRAINT FK_8E9C9FC099200CBC FOREIGN KEY (user_sondage_reponse_id) REFERENCES user_sondage_reponse (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_reponse_reponse ADD CONSTRAINT FK_8E9C9FC0CF18BB82 FOREIGN KEY (reponse_id) REFERENCES reponse (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_result ADD CONSTRAINT FK_CDC4B1D5F1B7D1A2 FOREIGN KEY (sonde_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_sondage_result ADD CONSTRAINT FK_CDC4B1D5BAF4AE56 FOREIGN KEY (sondage_id) REFERENCES sondage (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE test');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reponse_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE sondage_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_question_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE user_sondage_reponse_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_sondage_result_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE test_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE test (id INT NOT NULL, test VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494E553E212E');
        $this->addSql('ALTER TABLE question DROP CONSTRAINT FK_B6F7494EBAF4AE56');
        $this->addSql('ALTER TABLE reponse DROP CONSTRAINT FK_5FB6DEC71E27F6BF');
        $this->addSql('ALTER TABLE user_sondage_reponse DROP CONSTRAINT FK_2D6F17321E27F6BF');
        $this->addSql('ALTER TABLE user_sondage_reponse DROP CONSTRAINT FK_2D6F17328CD4AB76');
        $this->addSql('ALTER TABLE user_sondage_reponse_reponse DROP CONSTRAINT FK_8E9C9FC099200CBC');
        $this->addSql('ALTER TABLE user_sondage_reponse_reponse DROP CONSTRAINT FK_8E9C9FC0CF18BB82');
        $this->addSql('ALTER TABLE user_sondage_result DROP CONSTRAINT FK_CDC4B1D5F1B7D1A2');
        $this->addSql('ALTER TABLE user_sondage_result DROP CONSTRAINT FK_CDC4B1D5BAF4AE56');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE sondage');
        $this->addSql('DROP TABLE type_question');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_sondage_reponse');
        $this->addSql('DROP TABLE user_sondage_reponse_reponse');
        $this->addSql('DROP TABLE user_sondage_result');
    }
}
