<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231024100321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, content JSON NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags_projects (tags_id INT NOT NULL, projects_id INT NOT NULL, INDEX IDX_36052FBD8D7B4FB4 (tags_id), INDEX IDX_36052FBD1EDE0F55 (projects_id), PRIMARY KEY(tags_id, projects_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD8D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tags_projects ADD CONSTRAINT FK_36052FBD1EDE0F55 FOREIGN KEY (projects_id) REFERENCES projects (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projects DROP tags');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD8D7B4FB4');
        $this->addSql('ALTER TABLE tags_projects DROP FOREIGN KEY FK_36052FBD1EDE0F55');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE tags_projects');
        $this->addSql('ALTER TABLE projects ADD tags JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
