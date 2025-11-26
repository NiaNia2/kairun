<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124144529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_de_repas DROP FOREIGN KEY FK_B6DB291A22B77797');
        $this->addSql('DROP INDEX IDX_B6DB291A22B77797 ON type_de_repas');
        $this->addSql('ALTER TABLE type_de_repas DROP type_de_repas_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_de_repas ADD type_de_repas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_de_repas ADD CONSTRAINT FK_B6DB291A22B77797 FOREIGN KEY (type_de_repas_id) REFERENCES type_de_repas (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_B6DB291A22B77797 ON type_de_repas (type_de_repas_id)');
    }
}
