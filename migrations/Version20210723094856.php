<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723094856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE speler (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, rating VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wedstrijd (id INT AUTO_INCREMENT NOT NULL, speler_half_id INT NOT NULL, speler_heel_id INT NOT NULL, winnaar_id INT DEFAULT NULL, timestamp DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4720FB2FD29DB9FB (speler_half_id), INDEX IDX_4720FB2F2BA179F8 (speler_heel_id), INDEX IDX_4720FB2F26FBB26E (winnaar_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE wedstrijd ADD CONSTRAINT FK_4720FB2FD29DB9FB FOREIGN KEY (speler_half_id) REFERENCES speler (id)');
        $this->addSql('ALTER TABLE wedstrijd ADD CONSTRAINT FK_4720FB2F2BA179F8 FOREIGN KEY (speler_heel_id) REFERENCES speler (id)');
        $this->addSql('ALTER TABLE wedstrijd ADD CONSTRAINT FK_4720FB2F26FBB26E FOREIGN KEY (winnaar_id) REFERENCES speler (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE wedstrijd DROP FOREIGN KEY FK_4720FB2FD29DB9FB');
        $this->addSql('ALTER TABLE wedstrijd DROP FOREIGN KEY FK_4720FB2F2BA179F8');
        $this->addSql('ALTER TABLE wedstrijd DROP FOREIGN KEY FK_4720FB2F26FBB26E');
        $this->addSql('DROP TABLE speler');
        $this->addSql('DROP TABLE wedstrijd');
    }
}
