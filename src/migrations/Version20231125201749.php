<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231125201749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE plugintest');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656604584665A');
        $this->addSql('DROP INDEX IDX_4B3656604584665A ON stock');
        $this->addSql('ALTER TABLE stock CHANGE product_id ingrediant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656608AEA29A FOREIGN KEY (ingrediant_id) REFERENCES ingrediant (id)');
        $this->addSql('CREATE INDEX IDX_4B3656608AEA29A ON stock (ingrediant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plugintest (id INT AUTO_INCREMENT NOT NULL, plugin_name VARCHAR(255) CHARACTER SET utf8 NOT NULL COLLATE `utf8_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656608AEA29A');
        $this->addSql('DROP INDEX IDX_4B3656608AEA29A ON stock');
        $this->addSql('ALTER TABLE stock CHANGE ingrediant_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656604584665A FOREIGN KEY (product_id) REFERENCES ingrediant (id)');
        $this->addSql('CREATE INDEX IDX_4B3656604584665A ON stock (product_id)');
    }
}
