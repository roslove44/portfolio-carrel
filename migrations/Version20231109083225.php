<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231109083225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, projects_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, file_src VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6A1EDE0F55 (projects_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects (id INT AUTO_INCREMENT NOT NULL, client VARCHAR(255) NOT NULL, production_period DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', work_link VARCHAR(255) NOT NULL, proj_image VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, achievement VARCHAR(255) NOT NULL, priority INT NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projects_categories (projects_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_8C4CD7921EDE0F55 (projects_id), INDEX IDX_8C4CD792A21214B7 (categories_id), PRIMARY KEY(projects_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_6FBC94265E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_projects (tags_id INT NOT NULL, projects_id INT NOT NULL, INDEX IDX_36052FBD8D7B4FB4 (tags_id), INDEX IDX_36052FBD1EDE0F55 (projects_id), PRIMARY KEY(tags_id, projects_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD7921EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects_categories ADD CONSTRAINT FK_8C4CD792A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A1EDE0F55');
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD7921EDE0F55');
        $this->addSql('ALTER TABLE projects_categories DROP FOREIGN KEY FK_8C4CD792A21214B7');
        $this->addSql('ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD8D7B4FB4');
        $this->addSql('ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD1EDE0F55');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE projects');
        $this->addSql('DROP TABLE projects_categories');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_projects');
        $this->addSql('DROP TABLE users');
    }
}
