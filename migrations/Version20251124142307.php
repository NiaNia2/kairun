<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124142307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recette_ingredients (recette_id INT NOT NULL, ingredients_id INT NOT NULL, INDEX IDX_B413140689312FE9 (recette_id), INDEX IDX_B41314063EC4DCE (ingredients_id), PRIMARY KEY(recette_id, ingredients_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B413140689312FE9 FOREIGN KEY (recette_id) REFERENCES recette (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette_ingredients ADD CONSTRAINT FK_B41314063EC4DCE FOREIGN KEY (ingredients_id) REFERENCES ingredients (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recette ADD user_id INT NOT NULL, ADD objectif_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB6390157D1AD4 FOREIGN KEY (objectif_id) REFERENCES objectif (id)');
        $this->addSql('CREATE INDEX IDX_49BB6390A76ED395 ON recette (user_id)');
        $this->addSql('CREATE INDEX IDX_49BB6390157D1AD4 ON recette (objectif_id)');
        $this->addSql('ALTER TABLE type_de_repas ADD type_de_repas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_de_repas ADD CONSTRAINT FK_B6DB291A22B77797 FOREIGN KEY (type_de_repas_id) REFERENCES type_de_repas (id)');
        $this->addSql('CREATE INDEX IDX_B6DB291A22B77797 ON type_de_repas (type_de_repas_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B413140689312FE9');
        $this->addSql('ALTER TABLE recette_ingredients DROP FOREIGN KEY FK_B41314063EC4DCE');
        $this->addSql('DROP TABLE recette_ingredients');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390A76ED395');
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB6390157D1AD4');
        $this->addSql('DROP INDEX IDX_49BB6390A76ED395 ON recette');
        $this->addSql('DROP INDEX IDX_49BB6390157D1AD4 ON recette');
        $this->addSql('ALTER TABLE recette DROP user_id, DROP objectif_id');
        $this->addSql('ALTER TABLE type_de_repas DROP FOREIGN KEY FK_B6DB291A22B77797');
        $this->addSql('DROP INDEX IDX_B6DB291A22B77797 ON type_de_repas');
        $this->addSql('ALTER TABLE type_de_repas DROP type_de_repas_id');
    }
}
