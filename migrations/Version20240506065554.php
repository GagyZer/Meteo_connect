<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240506065554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alarme (id INT AUTO_INCREMENT NOT NULL, utilisateur_id INT NOT NULL, type VARCHAR(255) NOT NULL, valeur DOUBLE PRECISION NOT NULL, inf TINYINT(1) DEFAULT NULL, sup TINYINT(1) DEFAULT NULL, INDEX IDX_17CC4B75FB88E14F (utilisateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alarme ADD CONSTRAINT FK_17CC4B75FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alarme DROP FOREIGN KEY FK_17CC4B75FB88E14F');
        $this->addSql('DROP TABLE alarme');
    }
}
