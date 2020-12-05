<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201205213906 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D3656A4FAD56E62 ON account (iban)');
        $this->addSql('ALTER TABLE beneficiary CHANGE user_id user_id INT NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7ABF446AFAD56E62 ON beneficiary (iban)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_7D3656A4FAD56E62 ON account');
        $this->addSql('DROP INDEX UNIQ_7ABF446AFAD56E62 ON beneficiary');
        $this->addSql('ALTER TABLE beneficiary CHANGE user_id user_id INT DEFAULT NULL');
    }
}
