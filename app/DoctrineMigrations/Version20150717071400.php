<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150717071400 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE access_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_B6A2DD685F37A13B (token), INDEX IDX_B6A2DD6819EB6921 (client_id), INDEX IDX_B6A2DD68A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE auth_code (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, redirect_uri LONGTEXT NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5933D02C5F37A13B (token), INDEX IDX_5933D02C19EB6921 (client_id), INDEX IDX_5933D02CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_2246507BA76ED395 (user_id), UNIQUE INDEX UNIQ_2246507B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, random_id VARCHAR(255) NOT NULL, redirect_uris LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', secret VARCHAR(255) NOT NULL, allowed_grant_types LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE basket_order (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE order_item (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, product_id INT DEFAULT NULL, order_id INT DEFAULT NULL, quantity INT NOT NULL, INDEX IDX_52EA1F09A76ED395 (user_id), UNIQUE INDEX UNIQ_52EA1F094584665A (product_id), INDEX IDX_52EA1F098D9F6D38 (order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producent (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(1024) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, producent_id INT DEFAULT NULL, name VARCHAR(1024) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(10, 0) NOT NULL, INDEX IDX_D34A04AD853A965C (producent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(255) NOT NULL, expires_at INT DEFAULT NULL, scope VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_C74F21955F37A13B (token), INDEX IDX_C74F219519EB6921 (client_id), INDEX IDX_C74F2195A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE codifico_user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, salt VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', UNIQUE INDEX number_idx (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD6819EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE access_token ADD CONSTRAINT FK_B6A2DD68A76ED395 FOREIGN KEY (user_id) REFERENCES codifico_user (id)');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02C19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE auth_code ADD CONSTRAINT FK_5933D02CA76ED395 FOREIGN KEY (user_id) REFERENCES codifico_user (id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507BA76ED395 FOREIGN KEY (user_id) REFERENCES codifico_user (id)');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09A76ED395 FOREIGN KEY (user_id) REFERENCES codifico_user (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F098D9F6D38 FOREIGN KEY (order_id) REFERENCES basket_order (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD853A965C FOREIGN KEY (producent_id) REFERENCES producent (id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F219519EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE refresh_token ADD CONSTRAINT FK_C74F2195A76ED395 FOREIGN KEY (user_id) REFERENCES codifico_user (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD6819EB6921');
        $this->addSql('ALTER TABLE auth_code DROP FOREIGN KEY FK_5933D02C19EB6921');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F219519EB6921');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F098D9F6D38');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD853A965C');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507B4584665A');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F094584665A');
        $this->addSql('ALTER TABLE access_token DROP FOREIGN KEY FK_B6A2DD68A76ED395');
        $this->addSql('ALTER TABLE auth_code DROP FOREIGN KEY FK_5933D02CA76ED395');
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507BA76ED395');
        $this->addSql('ALTER TABLE order_item DROP FOREIGN KEY FK_52EA1F09A76ED395');
        $this->addSql('ALTER TABLE refresh_token DROP FOREIGN KEY FK_C74F2195A76ED395');
        $this->addSql('DROP TABLE access_token');
        $this->addSql('DROP TABLE auth_code');
        $this->addSql('DROP TABLE basket');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE basket_order');
        $this->addSql('DROP TABLE order_item');
        $this->addSql('DROP TABLE producent');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE codifico_user');
    }
}
