<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603142845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN location_id TO location_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD CONSTRAINT FK_8D93D649918DB72 FOREIGN KEY (location_id_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D93D649918DB72 ON "user" (location_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD user_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD location_id_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP user_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP location_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD CONSTRAINT FK_9DD221009D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD CONSTRAINT FK_9DD22100918DB72 FOREIGN KEY (location_id_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DD221009D86650F ON waste_collection (user_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DD22100918DB72 ON waste_collection (location_id_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT FK_9DD221009D86650F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT FK_9DD22100918DB72
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9DD221009D86650F
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9DD22100918DB72
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD user_id BIGINT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD location_id BIGINT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP user_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP location_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP CONSTRAINT FK_8D93D649918DB72
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D93D649918DB72
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" RENAME COLUMN location_id_id TO location_id
        SQL);
    }
}
