<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230302230353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE daily_report DROP FOREIGN KEY FK_4511402819EB6921');
        $this->addSql('ALTER TABLE daily_report DROP FOREIGN KEY FK_45114028350035DC');
        $this->addSql('DROP TABLE daily_report');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE daily_report (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, weight_id INT DEFAULT NULL, date DATE NOT NULL, UNIQUE INDEX UNIQ_45114028350035DC (weight_id), INDEX IDX_4511402819EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE daily_report ADD CONSTRAINT FK_4511402819EB6921 FOREIGN KEY (client_id) REFERENCES user__clients (id)');
        $this->addSql('ALTER TABLE daily_report ADD CONSTRAINT FK_45114028350035DC FOREIGN KEY (weight_id) REFERENCES data__weights (id)');
    }
}
