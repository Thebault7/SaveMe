<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204092526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_photo (category_id INT NOT NULL, photo_id INT NOT NULL, INDEX IDX_FC84CB5112469DE2 (category_id), INDEX IDX_FC84CB517E9E4C8C (photo_id), PRIMARY KEY(category_id, photo_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, proprietaire_id INT NOT NULL, titre VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, date DATETIME NOT NULL, lieu VARCHAR(255) DEFAULT NULL, INDEX IDX_14B7841876C50E4A (proprietaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category_photo ADD CONSTRAINT FK_FC84CB5112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_photo ADD CONSTRAINT FK_FC84CB517E9E4C8C FOREIGN KEY (photo_id) REFERENCES photo (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841876C50E4A FOREIGN KEY (proprietaire_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category_photo DROP FOREIGN KEY FK_FC84CB5112469DE2');
        $this->addSql('ALTER TABLE category_photo DROP FOREIGN KEY FK_FC84CB517E9E4C8C');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841876C50E4A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_photo');
        $this->addSql('DROP TABLE photo');
        $this->addSql('DROP TABLE user');
    }
}
