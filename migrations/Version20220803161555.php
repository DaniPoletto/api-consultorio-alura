<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220803161555 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE especialidade (id INT AUTO_INCREMENT NOT NULL, descricao VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medico ADD especialidade_id INT NOT NULL');
        $this->addSql('ALTER TABLE medico ADD CONSTRAINT FK_34E5914C3BA9BFA5 FOREIGN KEY (especialidade_id) REFERENCES especialidade (id)');
        $this->addSql('CREATE INDEX IDX_34E5914C3BA9BFA5 ON medico (especialidade_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE medico DROP FOREIGN KEY FK_34E5914C3BA9BFA5');
        $this->addSql('DROP TABLE especialidade');
        $this->addSql('DROP INDEX IDX_34E5914C3BA9BFA5 ON medico');
        $this->addSql('ALTER TABLE medico DROP especialidade_id');
    }
}
