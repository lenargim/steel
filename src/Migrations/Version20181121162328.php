<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181121162328 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('
        ALTER TABLE pages ADD FULLTEXT fulltext_index(h1, menutitle, description_text, specification);
        ');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
