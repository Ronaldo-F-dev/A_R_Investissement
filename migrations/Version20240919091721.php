<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240919091721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plans (id INT AUTO_INCREMENT NOT NULL, montant_min DOUBLE PRECISION NOT NULL, montant_max DOUBLE PRECISION NOT NULL, gain DOUBLE PRECISION NOT NULL, duree INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD parrain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE2A7A37 FOREIGN KEY (parrain_id) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9DE2A7A37 ON users (parrain_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE plans');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9DE2A7A37');
        $this->addSql('DROP INDEX UNIQ_1483A5E9DE2A7A37 ON users');
        $this->addSql('ALTER TABLE users DROP parrain_id');
    }
}
