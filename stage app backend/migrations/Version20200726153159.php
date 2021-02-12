<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200726153159 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD253AFA6CB');
        $this->addSql('DROP INDEX IDX_E19D9AD253AFA6CB ON service');
        $this->addSql('ALTER TABLE service DROP service_niveau_id');
        $this->addSql('ALTER TABLE service_niveau ADD service_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_niveau ADD CONSTRAINT FK_7518F65DED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('CREATE INDEX IDX_7518F65DED5CA9E6 ON service_niveau (service_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service ADD service_niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD253AFA6CB FOREIGN KEY (service_niveau_id) REFERENCES service_niveau (id)');
        $this->addSql('CREATE INDEX IDX_E19D9AD253AFA6CB ON service (service_niveau_id)');
        $this->addSql('ALTER TABLE service_niveau DROP FOREIGN KEY FK_7518F65DED5CA9E6');
        $this->addSql('DROP INDEX IDX_7518F65DED5CA9E6 ON service_niveau');
        $this->addSql('ALTER TABLE service_niveau DROP service_id');
    }
}
