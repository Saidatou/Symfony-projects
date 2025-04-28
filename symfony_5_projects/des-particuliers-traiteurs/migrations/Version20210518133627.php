<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210518133627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes ADD categories_id INT DEFAULT NULL, ADD users_id INT NOT NULL');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489AA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE ventes ADD CONSTRAINT FK_64EC489A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_64EC489AA21214B7 ON ventes (categories_id)');
        $this->addSql('CREATE INDEX IDX_64EC489A67B3B43D ON ventes (users_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489AA21214B7');
        $this->addSql('ALTER TABLE ventes DROP FOREIGN KEY FK_64EC489A67B3B43D');
        $this->addSql('DROP INDEX IDX_64EC489AA21214B7 ON ventes');
        $this->addSql('DROP INDEX IDX_64EC489A67B3B43D ON ventes');
        $this->addSql('ALTER TABLE ventes DROP categories_id, DROP users_id');
    }
}
