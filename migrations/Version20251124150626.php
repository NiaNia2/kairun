<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124150626 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette ADD type_de_repas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recette ADD CONSTRAINT FK_49BB639022B77797 FOREIGN KEY (type_de_repas_id) REFERENCES type_de_repas (id)');
        $this->addSql('CREATE INDEX IDX_49BB639022B77797 ON recette (type_de_repas_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recette DROP FOREIGN KEY FK_49BB639022B77797');
        $this->addSql('DROP INDEX IDX_49BB639022B77797 ON recette');
        $this->addSql('ALTER TABLE recette DROP type_de_repas_id');
    }
}
