<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150914155936 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD853A965C');
        $this->addSql('CREATE TABLE supplier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(1024) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE producent');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD853A965C');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD853A965C FOREIGN KEY (producent_id) REFERENCES supplier (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD853A965C');
        $this->addSql('CREATE TABLE producent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(1024) NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE supplier');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD853A965C');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD853A965C FOREIGN KEY (producent_id) REFERENCES producent (id)');
    }
}
