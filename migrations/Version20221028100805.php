<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028100805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F893C0BE965 ON picture (filename)');
        $this->addSql('ALTER TABLE trick CHANGE additional_image_filename additional_image_filename VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF48AD8D0 FOREIGN KEY (additional_image_filename) REFERENCES picture (filename)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91EF48AD8D0 ON trick (additional_image_filename)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_16DB4F893C0BE965 ON picture');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EF48AD8D0');
        $this->addSql('DROP INDEX UNIQ_D8F0A91EF48AD8D0 ON trick');
        $this->addSql('ALTER TABLE trick CHANGE additional_image_filename additional_image_filename VARCHAR(255) NOT NULL');
    }
}
