<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create todo and audit tables
 */
final class Version20211013110322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create todo and audit tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE audit (id INT AUTO_INCREMENT NOT NULL, task_id INT NOT NULL, action VARCHAR(16) NOT NULL, description VARCHAR(255) NOT NULL, performed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE todo (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, completed TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE audit');
        $this->addSql('DROP TABLE todo');
    }
}
