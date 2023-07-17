<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717222211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE news ADD content TEXT NOT NULL');
        $this->addSql('ALTER TABLE news ADD source_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news RENAME COLUMN name TO title');
        $this->addSql('ALTER TABLE news RENAME COLUMN description TO preview');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE news ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE news ADD description TEXT NOT NULL');
        $this->addSql('ALTER TABLE news DROP title');
        $this->addSql('ALTER TABLE news DROP preview');
        $this->addSql('ALTER TABLE news DROP content');
        $this->addSql('ALTER TABLE news DROP source_url');
    }
}
