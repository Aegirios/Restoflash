<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121205021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resto ADD logo VARCHAR(255) DEFAULT NULL, ADD slogan VARCHAR(255) DEFAULT NULL, ADD adress VARCHAR(255) DEFAULT NULL, ADD telephone VARCHAR(255) DEFAULT NULL, ADD contact_mail VARCHAR(255) DEFAULT NULL, ADD gps DOUBLE PRECISION DEFAULT NULL, ADD opening_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD closing_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', ADD is_reservation_possible TINYINT(1) DEFAULT NULL, ADD cgu LONGTEXT DEFAULT NULL, ADD confidentiality_policy LONGTEXT DEFAULT NULL, ADD reservation_policy LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resto DROP logo, DROP slogan, DROP adress, DROP telephone, DROP contact_mail, DROP gps, DROP opening_hours, DROP closing_hours, DROP is_reservation_possible, DROP cgu, DROP confidentiality_policy, DROP reservation_policy');
    }
}
