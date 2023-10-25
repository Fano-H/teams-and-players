<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025192830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE operation (id INT AUTO_INCREMENT NOT NULL, seller_id INT NOT NULL, buyer_id INT NOT NULL, player_id INT NOT NULL, type_op VARCHAR(255) NOT NULL, amount DOUBLE PRECISION NOT NULL, INDEX IDX_1981A66D8DE820D9 (seller_id), INDEX IDX_1981A66D6C755722 (buyer_id), INDEX IDX_1981A66D99E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D8DE820D9 FOREIGN KEY (seller_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D6C755722 FOREIGN KEY (buyer_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D99E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D8DE820D9');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D6C755722');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D99E6F5DF');
        $this->addSql('DROP TABLE operation');
    }
}
