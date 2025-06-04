<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250604100724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ALTER waste_type_id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ALTER waste_collection_id TYPE INT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ADD CONSTRAINT FK_5ADA3DCA820363B6 FOREIGN KEY (waste_collection_id) REFERENCES waste_collection (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ADD CONSTRAINT FK_5ADA3DCA21B47B45 FOREIGN KEY (waste_type_id) REFERENCES waste_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5ADA3DCA820363B6 ON waste_item (waste_collection_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_5ADA3DCA21B47B45 ON waste_item (waste_type_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_type DROP label
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_type ADD label VARCHAR(30) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item DROP CONSTRAINT FK_5ADA3DCA820363B6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item DROP CONSTRAINT FK_5ADA3DCA21B47B45
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5ADA3DCA820363B6
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_5ADA3DCA21B47B45
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ALTER waste_collection_id TYPE BIGINT
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_item ALTER waste_type_id TYPE BIGINT
        SQL);
    }
}
