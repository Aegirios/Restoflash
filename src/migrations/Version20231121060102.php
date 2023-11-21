<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121060102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_recipe (recipe_source INT NOT NULL, recipe_target INT NOT NULL, INDEX IDX_5796AF16D2946152 (recipe_source), INDEX IDX_5796AF16CB7131DD (recipe_target), PRIMARY KEY(recipe_source, recipe_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_recipe ADD CONSTRAINT FK_5796AF16D2946152 FOREIGN KEY (recipe_source) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_recipe ADD CONSTRAINT FK_5796AF16CB7131DD FOREIGN KEY (recipe_target) REFERENCES recipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_recipe DROP FOREIGN KEY FK_5796AF16D2946152');
        $this->addSql('ALTER TABLE recipe_recipe DROP FOREIGN KEY FK_5796AF16CB7131DD');
        $this->addSql('DROP TABLE recipe_recipe');
    }
}
