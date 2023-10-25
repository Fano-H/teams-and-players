<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231025202407 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D8DE820D9');
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D6C755722');
        $this->addSql('DROP INDEX IDX_1981A66D6C755722 ON operation');
        $this->addSql('DROP INDEX IDX_1981A66D8DE820D9 ON operation');
        $this->addSql('ALTER TABLE operation ADD operator_id INT NOT NULL, DROP seller_id, DROP buyer_id');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D584598A3 FOREIGN KEY (operator_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_1981A66D584598A3 ON operation (operator_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE operation DROP FOREIGN KEY FK_1981A66D584598A3');
        $this->addSql('DROP INDEX IDX_1981A66D584598A3 ON operation');
        $this->addSql('ALTER TABLE operation ADD buyer_id INT NOT NULL, CHANGE operator_id seller_id INT NOT NULL');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D8DE820D9 FOREIGN KEY (seller_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE operation ADD CONSTRAINT FK_1981A66D6C755722 FOREIGN KEY (buyer_id) REFERENCES team (id)');
        $this->addSql('CREATE INDEX IDX_1981A66D6C755722 ON operation (buyer_id)');
        $this->addSql('CREATE INDEX IDX_1981A66D8DE820D9 ON operation (seller_id)');
    }
}
