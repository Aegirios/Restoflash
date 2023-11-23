<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231122055019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resto CHANGE opening_hours opening_hours JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE closing_hours closing_hours JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resto CHANGE opening_hours opening_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE closing_hours closing_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
