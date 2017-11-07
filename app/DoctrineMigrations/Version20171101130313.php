<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

class Version20171101130313 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.user ALTER email DROP NOT NULL');
        $this->addSql('ALTER TABLE public.user ALTER email_canonical DROP NOT NULL');
        $this->addSql('ALTER TABLE public.user ALTER password DROP NOT NULL');
//        $this->addSql('ALTER TABLE public.user ALTER friends DROP NOT NULL');
        
        $this->addSql('ALTER TABLE public.user ALTER email SET DEFAULT null');
        $this->addSql('ALTER TABLE public.user ALTER email_canonical SET DEFAULT null');
        $this->addSql('ALTER TABLE public.user ALTER password SET DEFAULT null');
//        $this->addSql('ALTER TABLE public.user ALTER friends SET DEFAULT null');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE public.user ALTER email DROP DEFAULT');
        $this->addSql('ALTER TABLE public.user ALTER email_canonical DROP DEFAULT');
        $this->addSql('ALTER TABLE public.user ALTER password DROP DEFAULT');
//        $this->addSql('ALTER TABLE public.user ALTER friends DROP DEFAULT');
        
        $this->addSql('ALTER TABLE public.user ALTER email SET NOT NULL');
        $this->addSql('ALTER TABLE public.user ALTER email_canonical SET NOT NULL');
        $this->addSql('ALTER TABLE public.user ALTER password SET NOT NULL');
//        $this->addSql('ALTER TABLE public.user ALTER friends SET NOT NULL');
    }
}
