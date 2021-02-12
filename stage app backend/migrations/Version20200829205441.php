<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200829205441 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, ref_article VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, quantite_total INT NOT NULL, activte_article_ser_achat1 TINYINT(1) DEFAULT NULL, activte_article_ser_achat0 TINYINT(1) DEFAULT NULL, activte_article_ser_transitaire0 TINYINT(1) DEFAULT NULL, activte_article_ser_info1 TINYINT(1) DEFAULT NULL, activte_article_ser_info0 TINYINT(1) DEFAULT NULL, INDEX IDX_23A0E66BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commantaire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article_id INT DEFAULT NULL, commentaire VARCHAR(255) NOT NULL, INDEX IDX_93BF4CAFA76ED395 (user_id), INDEX IDX_93BF4CAF7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C53D045FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, article_id INT DEFAULT NULL, ref_machine VARCHAR(255) NOT NULL, nom_machine VARCHAR(255) NOT NULL, quantite_total INT NOT NULL, prix DOUBLE PRECISION NOT NULL, image VARCHAR(255) DEFAULT NULL, activte_article_ser_achat1 TINYINT(1) DEFAULT NULL, activte_article_ser_achat0 TINYINT(1) DEFAULT NULL, activte_article_ser_transitaire0 TINYINT(1) DEFAULT NULL, activte_article_ser_info1 TINYINT(1) DEFAULT NULL, activte_article_ser_info0 TINYINT(1) DEFAULT NULL, INDEX IDX_D79572D97294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model_nomenclature (id INT AUTO_INCREMENT NOT NULL, model_id INT DEFAULT NULL, nomenclature_id INT DEFAULT NULL, INDEX IDX_D0489A387975B7E7 (model_id), INDEX IDX_D0489A3890BFD4B8 (nomenclature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nomenclature (id INT AUTO_INCREMENT NOT NULL, type_nomenclature_id INT DEFAULT NULL, ref_intern VARCHAR(255) NOT NULL, nom_fr VARCHAR(255) NOT NULL, nom_ar VARCHAR(255) NOT NULL, nom_en VARCHAR(255) NOT NULL, code_short VARCHAR(255) NOT NULL, designation VARCHAR(255) NOT NULL, code_sage VARCHAR(255) NOT NULL, image VARCHAR(255) DEFAULT NULL, INDEX IDX_799A3652678B40CC (type_nomenclature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, show_in VARCHAR(255) NOT NULL, seen TINYINT(1) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_BF5476CAA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_niveau (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, niveau_id INT DEFAULT NULL, INDEX IDX_7518F65DED5CA9E6 (service_id), INDEX IDX_7518F65DB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_nomenclature (id INT AUTO_INCREMENT NOT NULL, type_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, role_id INT DEFAULT NULL, cin VARCHAR(255) NOT NULL, full_name VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(45) NOT NULL, tel VARCHAR(255) NOT NULL, niveau_scolaire VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9D60322AC (role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE commantaire ADD CONSTRAINT FK_93BF4CAFA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE commantaire ADD CONSTRAINT FK_93BF4CAF7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D97294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE model_nomenclature ADD CONSTRAINT FK_D0489A387975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE model_nomenclature ADD CONSTRAINT FK_D0489A3890BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE nomenclature ADD CONSTRAINT FK_799A3652678B40CC FOREIGN KEY (type_nomenclature_id) REFERENCES type_nomenclature (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CAA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE service_niveau ADD CONSTRAINT FK_7518F65DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE service_niveau ADD CONSTRAINT FK_7518F65DB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9D60322AC FOREIGN KEY (role_id) REFERENCES service_niveau (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commantaire DROP FOREIGN KEY FK_93BF4CAF7294869C');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D97294869C');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('ALTER TABLE model_nomenclature DROP FOREIGN KEY FK_D0489A387975B7E7');
        $this->addSql('ALTER TABLE model_nomenclature DROP FOREIGN KEY FK_D0489A3890BFD4B8');
        $this->addSql('ALTER TABLE service_niveau DROP FOREIGN KEY FK_7518F65DB3E9C81');
        $this->addSql('ALTER TABLE service_niveau DROP FOREIGN KEY FK_7518F65DED5CA9E6');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9D60322AC');
        $this->addSql('ALTER TABLE nomenclature DROP FOREIGN KEY FK_799A3652678B40CC');
        $this->addSql('ALTER TABLE commantaire DROP FOREIGN KEY FK_93BF4CAFA76ED395');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FA76ED395');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CAA76ED395');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commantaire');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE model_nomenclature');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE nomenclature');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE service_niveau');
        $this->addSql('DROP TABLE type_nomenclature');
        $this->addSql('DROP TABLE users');
    }
}
