<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210819083016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE borrow (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, date_start DATETIME NOT NULL, date_finish DATETIME NOT NULL, INDEX IDX_55DBA8B0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE borrow_film (borrow_id INT NOT NULL, film_id INT NOT NULL, INDEX IDX_1698407D4C006C8 (borrow_id), INDEX IDX_1698407567F5183 (film_id), PRIMARY KEY(borrow_id, film_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE borrow ADD CONSTRAINT FK_55DBA8B0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE borrow_film ADD CONSTRAINT FK_1698407D4C006C8 FOREIGN KEY (borrow_id) REFERENCES borrow (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE borrow_film ADD CONSTRAINT FK_1698407567F5183 FOREIGN KEY (film_id) REFERENCES film (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE borrow_film DROP FOREIGN KEY FK_1698407D4C006C8');
        $this->addSql('DROP TABLE borrow');
        $this->addSql('DROP TABLE borrow_film');
    }
}
