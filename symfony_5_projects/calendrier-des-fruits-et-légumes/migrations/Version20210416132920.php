<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210416132920 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fruit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, mois VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fruits (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, mois VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mois (id INT AUTO_INCREMENT NOT NULL, fruit_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_D6B08CB7BAC115F0 (fruit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mois ADD CONSTRAINT FK_D6B08CB7BAC115F0 FOREIGN KEY (fruit_id) REFERENCES fruit (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mois DROP FOREIGN KEY FK_D6B08CB7BAC115F0');
        $this->addSql('DROP TABLE fruit');
        $this->addSql('DROP TABLE fruits');
        $this->addSql('DROP TABLE mois');
    }
}
