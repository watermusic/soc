<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150323084634 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE soc_player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, verein VARCHAR(255) NOT NULL, position VARCHAR(255) NOT NULL, vk_preis DOUBLE PRECISION NOT NULL, ek_preis DOUBLE PRECISION NOT NULL, kaeufer VARCHAR(255) NOT NULL, note DOUBLE PRECISION NOT NULL, punkte DOUBLE PRECISION NOT NULL, INDEX IDX_DF0CFD585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soc_score (id INT AUTO_INCREMENT NOT NULL, player INT DEFAULT NULL, matchday SMALLINT NOT NULL, score INT NOT NULL, INDEX IDX_95D175BF98197A65 (player), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE soc_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, username_canonical VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_canonical VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, locked TINYINT(1) NOT NULL, expired TINYINT(1) NOT NULL, expires_at DATETIME DEFAULT NULL, confirmation_token VARCHAR(255) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', credentials_expired TINYINT(1) NOT NULL, credentials_expire_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_A2F759AD92FC23A8 (username_canonical), UNIQUE INDEX UNIQ_A2F759ADA0D96FBF (email_canonical), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE soc_score ADD CONSTRAINT FK_95D175BF98197A65 FOREIGN KEY (player) REFERENCES soc_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE soc_score DROP FOREIGN KEY FK_95D175BF98197A65');
        $this->addSql('DROP TABLE soc_player');
        $this->addSql('DROP TABLE soc_score');
        $this->addSql('DROP TABLE soc_user');
    }
}
