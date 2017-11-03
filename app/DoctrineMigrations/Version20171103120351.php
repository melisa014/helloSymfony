<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171103120351 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.code ALTER is_login DROP NOT NULL');
        $this->addSql('ALTER TABLE public.code ALTER is_login SET DEFAULT false');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.code ALTER is_login DROP DEFAULT');
        $this->addSql('ALTER TABLE public.code ALTER is_login SET NOT NULL');
    }
}
