<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220102211951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE permission_groups_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "table_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE table_element_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE permission_groups (id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, group_color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "table" (id INT NOT NULL, table_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE table_permission_groups (table_id INT NOT NULL, permission_groups_id INT NOT NULL, PRIMARY KEY(table_id, permission_groups_id))');
        $this->addSql('CREATE INDEX IDX_9D8897E5ECFF285C ON table_permission_groups (table_id)');
        $this->addSql('CREATE INDEX IDX_9D8897E52DC013A3 ON table_permission_groups (permission_groups_id)');
        $this->addSql('CREATE TABLE table_element (id INT NOT NULL, parent_table_id INT DEFAULT NULL, content JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_43731F367332848D ON table_element (parent_table_id)');
        $this->addSql('CREATE TABLE user_permission_groups (user_id INT NOT NULL, permission_groups_id INT NOT NULL, PRIMARY KEY(user_id, permission_groups_id))');
        $this->addSql('CREATE INDEX IDX_C1A825B0A76ED395 ON user_permission_groups (user_id)');
        $this->addSql('CREATE INDEX IDX_C1A825B02DC013A3 ON user_permission_groups (permission_groups_id)');
        $this->addSql('ALTER TABLE table_permission_groups ADD CONSTRAINT FK_9D8897E5ECFF285C FOREIGN KEY (table_id) REFERENCES "table" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE table_permission_groups ADD CONSTRAINT FK_9D8897E52DC013A3 FOREIGN KEY (permission_groups_id) REFERENCES permission_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE table_element ADD CONSTRAINT FK_43731F367332848D FOREIGN KEY (parent_table_id) REFERENCES "table" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_permission_groups ADD CONSTRAINT FK_C1A825B0A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_permission_groups ADD CONSTRAINT FK_C1A825B02DC013A3 FOREIGN KEY (permission_groups_id) REFERENCES permission_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE table_permission_groups DROP CONSTRAINT FK_9D8897E52DC013A3');
        $this->addSql('ALTER TABLE user_permission_groups DROP CONSTRAINT FK_C1A825B02DC013A3');
        $this->addSql('ALTER TABLE table_permission_groups DROP CONSTRAINT FK_9D8897E5ECFF285C');
        $this->addSql('ALTER TABLE table_element DROP CONSTRAINT FK_43731F367332848D');
        $this->addSql('DROP SEQUENCE permission_groups_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "table_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE table_element_id_seq CASCADE');
        $this->addSql('DROP TABLE permission_groups');
        $this->addSql('DROP TABLE "table"');
        $this->addSql('DROP TABLE table_permission_groups');
        $this->addSql('DROP TABLE table_element');
        $this->addSql('DROP TABLE user_permission_groups');
    }
}
