<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231120201511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingrediant (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, card_inside LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE menu_recipe (menu_id INT NOT NULL, recipe_id INT NOT NULL, INDEX IDX_9CFE9EFCCD7E912 (menu_id), INDEX IDX_9CFE9EF59D8A214 (recipe_id), PRIMARY KEY(menu_id, recipe_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, image LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, difficulty INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_ingrediant (recipe_id INT NOT NULL, ingrediant_id INT NOT NULL, INDEX IDX_25D856CF59D8A214 (recipe_id), INDEX IDX_25D856CF8AEA29A (ingrediant_id), PRIMARY KEY(recipe_id, ingrediant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE menu_recipe ADD CONSTRAINT FK_9CFE9EFCCD7E912 FOREIGN KEY (menu_id) REFERENCES menu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE menu_recipe ADD CONSTRAINT FK_9CFE9EF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingrediant ADD CONSTRAINT FK_25D856CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingrediant ADD CONSTRAINT FK_25D856CF8AEA29A FOREIGN KEY (ingrediant_id) REFERENCES ingrediant (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu_recipe DROP FOREIGN KEY FK_9CFE9EFCCD7E912');
        $this->addSql('ALTER TABLE menu_recipe DROP FOREIGN KEY FK_9CFE9EF59D8A214');
        $this->addSql('ALTER TABLE recipe_ingrediant DROP FOREIGN KEY FK_25D856CF59D8A214');
        $this->addSql('ALTER TABLE recipe_ingrediant DROP FOREIGN KEY FK_25D856CF8AEA29A');
        $this->addSql('DROP TABLE ingrediant');
        $this->addSql('DROP TABLE menu');
        $this->addSql('DROP TABLE menu_recipe');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_ingrediant');
    }
}
