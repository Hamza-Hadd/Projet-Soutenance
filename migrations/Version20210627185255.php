<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627185255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artwork ADD user_id_id INT DEFAULT NULL, DROP price');
        $this->addSql('ALTER TABLE artwork ADD CONSTRAINT FK_881FC5769D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_881FC5769D86650F ON artwork (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artwork DROP FOREIGN KEY FK_881FC5769D86650F');
        $this->addSql('DROP INDEX IDX_881FC5769D86650F ON artwork');
        $this->addSql('ALTER TABLE artwork ADD price DOUBLE PRECISION NOT NULL, DROP user_id_id');
    }
}
