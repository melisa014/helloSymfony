<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Создание таблицы users
 */
class Version20170925150339 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE users
                    (
                        id bigserial NOT NULL,
                        username character varying(35) NOT NULL,
                        email character varying(35),
                        CONSTRAINT users_pkey PRIMARY KEY (id),
                        CONSTRAINT login UNIQUE (username)
                    )'
                );
        
        $this->addSql('ALTER TABLE users
                OWNER to hellosymfony');   
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE users');
    }
}
