<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240916073310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post ADD link LONGTEXT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5A8A6C8D989D9B62 ON post (slug)');
        $this->addSql('ALTER TABLE rubrik CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(100) DEFAULT NULL, CHANGE opption opption VARCHAR(10) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_5A8A6C8D989D9B62 ON post');
        $this->addSql('ALTER TABLE post DROP link');
        $this->addSql('ALTER TABLE rubrik CHANGE name name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE avatar avatar VARCHAR(100) NOT NULL, CHANGE opption opption VARCHAR(100) DEFAULT NULL');
    }
}
