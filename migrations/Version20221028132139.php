<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028132139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FB281BE2E');
        $this->addSql('DROP INDEX IDX_1D5EF26FB281BE2E ON movie');
        $this->addSql('ALTER TABLE movie DROP trick_id');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89B281BE2E');
        $this->addSql('DROP INDEX IDX_16DB4F89B281BE2E ON picture');
        $this->addSql('ALTER TABLE picture DROP trick_id');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EA76ED395');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EF48AD8D0');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EFE54D947');
        $this->addSql('DROP INDEX UNIQ_D8F0A91EF48AD8D0 ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91EFE54D947 ON trick');
        $this->addSql('DROP INDEX IDX_D8F0A91EA76ED395 ON trick');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26FB281BE2E ON movie (trick_id)');
        $this->addSql('ALTER TABLE picture ADD trick_id INT NOT NULL');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('CREATE INDEX IDX_16DB4F89B281BE2E ON picture (trick_id)');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9D86650F');
        $this->addSql('DROP INDEX IDX_D8F0A91E9D86650F ON trick');
        $this->addSql('ALTER TABLE trick ADD group_id INT NOT NULL, ADD additional_image_filename VARCHAR(255) DEFAULT NULL, CHANGE user_id_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EF48AD8D0 FOREIGN KEY (additional_image_filename) REFERENCES picture (filename) ON UPDATE CASCADE');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EFE54D947 FOREIGN KEY (group_id) REFERENCES `group` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91EF48AD8D0 ON trick (additional_image_filename)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EFE54D947 ON trick (group_id)');
        $this->addSql('CREATE INDEX IDX_D8F0A91EA76ED395 ON trick (user_id)');
    }
}
