<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250603154558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT fk_9dd221009d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT fk_9dd22100918db72
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_9dd22100918db72
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX idx_9dd221009d86650f
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD user_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD location_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP user_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP location_id_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD CONSTRAINT FK_9DD22100A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD CONSTRAINT FK_9DD2210064D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DD22100A76ED395 ON waste_collection (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9DD2210064D218E ON waste_collection (location_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT FK_9DD22100A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection DROP CONSTRAINT FK_9DD2210064D218E
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9DD22100A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_9DD2210064D218E
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
            ALTER TABLE waste_collection ADD CONSTRAINT fk_9dd221009d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE waste_collection ADD CONSTRAINT fk_9dd22100918db72 FOREIGN KEY (location_id_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9dd22100918db72 ON waste_collection (location_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_9dd221009d86650f ON waste_collection (user_id_id)
        SQL);
    }
}
