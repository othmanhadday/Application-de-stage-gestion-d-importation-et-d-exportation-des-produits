<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200829205238 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE nomenclature_model');
        $this->addSql('DROP TABLE nomenclatures2models');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, article_id INT DEFAULT NULL, commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_67F068BCA76ED395 (user_id), INDEX IDX_67F068BC7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nomenclature_model (nomenclature_id INT NOT NULL, model_id INT NOT NULL, INDEX IDX_BB5728807975B7E7 (model_id), INDEX IDX_BB57288090BFD4B8 (nomenclature_id), PRIMARY KEY(nomenclature_id, model_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE nomenclatures2models (model_id INT NOT NULL, nomenclature_id INT NOT NULL, INDEX IDX_BE36BAB590BFD4B8 (nomenclature_id), INDEX IDX_BE36BAB57975B7E7 (model_id), PRIMARY KEY(model_id, nomenclature_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE nomenclature_model ADD CONSTRAINT FK_BB5728807975B7E7 FOREIGN KEY (model_id) REFERENCES model (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nomenclature_model ADD CONSTRAINT FK_BB57288090BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE nomenclatures2models ADD CONSTRAINT FK_BE36BAB57975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE nomenclatures2models ADD CONSTRAINT FK_BE36BAB590BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclature (id)');
    }
}
