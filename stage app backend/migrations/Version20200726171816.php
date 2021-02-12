<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726171816 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_niveau ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_niveau ADD CONSTRAINT FK_7518F65DB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_7518F65DB3E9C81 ON service_niveau (niveau_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_niveau DROP FOREIGN KEY FK_7518F65DB3E9C81');
        $this->addSql('DROP INDEX IDX_7518F65DB3E9C81 ON service_niveau');
        $this->addSql('ALTER TABLE service_niveau DROP niveau_id');
    }
}
