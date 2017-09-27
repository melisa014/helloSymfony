<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Изменение внешнего ключа таблицы articles с автора статей на user_id
 */
class Version20170926152044 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE articles DROP CONSTRAINT author_from_users');
        
        $this->addSql('ALTER TABLE articles ADD COLUMN user_id INTEGER');
        
        $this->addSql('ALTER TABLE articles ADD FOREIGN KEY (user_id) REFERENCES users (id)
                        ON UPDATE CASCADE
                        ON DELETE CASCADE');
                      

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY (user_id)');
        
        $this->addSql('ALTER TABLE articles DROP COLUMN user_id');
        
        $this->addSql('ALTER TABLE articles ADD FOREIGN KEY (author) REFERENCES users (username)
                        ON UPDATE CASCADE
                        ON DELETE CASCADE');
    }
}
