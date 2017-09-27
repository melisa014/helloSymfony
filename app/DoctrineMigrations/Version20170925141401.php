<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Создание таблицы articles и внешнего ключа с users
 */
class Version20170925141401 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE articles
            (
                id bigserial NOT NULL,
                name character varying(255) NOT NULL,
                content text NOT NULL,
                date timestamp without time zone,
                author character varying(35) NOT NULL,
                CONSTRAINT articles_pkey PRIMARY KEY (id),
                CONSTRAINT author_from_users FOREIGN KEY (author)
                    REFERENCES users (username) MATCH SIMPLE
                    ON UPDATE NO ACTION
                    ON DELETE NO ACTION
            )');
            
        $this->addSql('ALTER TABLE articles
                OWNER to hellosymfony');   

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE articles');

    }
}
