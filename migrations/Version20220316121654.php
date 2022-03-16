<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316121654 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "character" ALTER occupation TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE "character" ALTER occupation DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN "character".occupation IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE character ALTER occupation TYPE TEXT');
        $this->addSql('ALTER TABLE character ALTER occupation DROP DEFAULT');
        $this->addSql('COMMENT ON COLUMN character.occupation IS \'(DC2Type:array)\'');
    }
}
