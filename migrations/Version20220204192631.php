<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220204192631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_D34A04AD18F45C82');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, website_id, reference FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, website_id INTEGER NOT NULL, reference VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_D34A04AD18F45C82 FOREIGN KEY (website_id) REFERENCES website (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product (id, website_id, reference) SELECT id, website_id, reference FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD18F45C82 ON product (website_id)');
        $this->addSql('DROP INDEX IDX_D88926224584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__rating AS SELECT id, product_id, mark, author_user_mail, metadata FROM rating');
        $this->addSql('DROP TABLE rating');
        $this->addSql('CREATE TABLE rating (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, mark DOUBLE PRECISION NOT NULL, author_user_email VARCHAR(255) NOT NULL, metadata CLOB DEFAULT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_D88926224584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO rating (id, product_id, mark, author_user_email, metadata) SELECT id, product_id, mark, author_user_mail, metadata FROM __temp__rating');
        $this->addSql('DROP TABLE __temp__rating');
        $this->addSql('CREATE INDEX IDX_D88926224584665A ON rating (product_id)');
        $this->addSql('DROP INDEX IDX_5A108564A32EFC6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vote AS SELECT id, rating_id, is_up, author_user_email, metadata FROM vote');
        $this->addSql('DROP TABLE vote');
        $this->addSql('CREATE TABLE vote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, rating_id INTEGER NOT NULL, is_up BOOLEAN NOT NULL, author_user_email VARCHAR(255) NOT NULL COLLATE BINARY, metadata CLOB DEFAULT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_5A108564A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO vote (id, rating_id, is_up, author_user_email, metadata) SELECT id, rating_id, is_up, author_user_email, metadata FROM __temp__vote');
        $this->addSql('DROP TABLE __temp__vote');
        $this->addSql('CREATE INDEX IDX_5A108564A32EFC6 ON vote (rating_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_D34A04AD18F45C82');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, website_id, reference FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, website_id INTEGER NOT NULL, reference VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO product (id, website_id, reference) SELECT id, website_id, reference FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE INDEX IDX_D34A04AD18F45C82 ON product (website_id)');
        $this->addSql('DROP INDEX IDX_D88926224584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__rating AS SELECT id, product_id, mark, author_user_email, metadata FROM rating');
        $this->addSql('DROP TABLE rating');
        $this->addSql('CREATE TABLE rating (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER NOT NULL, mark DOUBLE PRECISION NOT NULL, author_user_mail VARCHAR(255) NOT NULL COLLATE BINARY, metadata CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO rating (id, product_id, mark, author_user_mail, metadata) SELECT id, product_id, mark, author_user_email, metadata FROM __temp__rating');
        $this->addSql('DROP TABLE __temp__rating');
        $this->addSql('CREATE INDEX IDX_D88926224584665A ON rating (product_id)');
        $this->addSql('DROP INDEX IDX_5A108564A32EFC6');
        $this->addSql('CREATE TEMPORARY TABLE __temp__vote AS SELECT id, rating_id, is_up, author_user_email, metadata FROM vote');
        $this->addSql('DROP TABLE vote');
        $this->addSql('CREATE TABLE vote (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, rating_id INTEGER NOT NULL, is_up BOOLEAN NOT NULL, author_user_email VARCHAR(255) NOT NULL, metadata CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO vote (id, rating_id, is_up, author_user_email, metadata) SELECT id, rating_id, is_up, author_user_email, metadata FROM __temp__vote');
        $this->addSql('DROP TABLE __temp__vote');
        $this->addSql('CREATE INDEX IDX_5A108564A32EFC6 ON vote (rating_id)');
    }
}
