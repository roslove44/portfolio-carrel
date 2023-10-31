<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231031162412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_categories (projects_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_8C4CD7921EDE0F55 (projects_id), INDEX IDX_8C4CD792A21214B7 (categories_id), PRIMARY KEY(projects_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD7921EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD792A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD7921EDE0F55');
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD792A21214B7');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE projects_categories');
    }
}
