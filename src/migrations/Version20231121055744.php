<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231121055744 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_ingrediant (recipe_id INT NOT NULL, ingrediant_id INT NOT NULL, INDEX IDX_25D856CF59D8A214 (recipe_id), INDEX IDX_25D856CF8AEA29A (ingrediant_id), PRIMARY KEY(recipe_id, ingrediant_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_ingrediant ADD CONSTRAINT FK_25D856CF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingrediant ADD CONSTRAINT FK_25D856CF8AEA29A FOREIGN KEY (ingrediant_id) REFERENCES ingrediant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE1359D8A214');
        $this->addSql('ALTER TABLE recipe_ingredient DROP FOREIGN KEY FK_22D1FE13933FE08C');
        $this->addSql('ALTER TABLE recipe_recipe_ingredient DROP FOREIGN KEY FK_F09816B759D8A214');
        $this->addSql('ALTER TABLE recipe_recipe_ingredient DROP FOREIGN KEY FK_F09816B73CAF64A');
        $this->addSql('DROP TABLE recipe_ingredient');
        $this->addSql('DROP TABLE recipe_recipe_ingredient');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_ingredient (id INT AUTO_INCREMENT NOT NULL, recipe_id INT DEFAULT NULL, ingredient_id INT DEFAULT NULL, INDEX IDX_22D1FE1359D8A214 (recipe_id), INDEX IDX_22D1FE13933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE recipe_recipe_ingredient (recipe_id INT NOT NULL, recipe_ingredient_id INT NOT NULL, INDEX IDX_F09816B759D8A214 (recipe_id), INDEX IDX_F09816B73CAF64A (recipe_ingredient_id), PRIMARY KEY(recipe_id, recipe_ingredient_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE1359D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_ingredient ADD CONSTRAINT FK_22D1FE13933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingrediant (id)');
        $this->addSql('ALTER TABLE recipe_recipe_ingredient ADD CONSTRAINT FK_F09816B759D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_recipe_ingredient ADD CONSTRAINT FK_F09816B73CAF64A FOREIGN KEY (recipe_ingredient_id) REFERENCES recipe_ingredient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_ingrediant DROP FOREIGN KEY FK_25D856CF59D8A214');
        $this->addSql('ALTER TABLE recipe_ingrediant DROP FOREIGN KEY FK_25D856CF8AEA29A');
        $this->addSql('DROP TABLE recipe_ingrediant');
    }
}
