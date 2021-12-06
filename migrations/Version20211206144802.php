<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206144802 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE horaires (id INT AUTO_INCREMENT NOT NULL, poubelles_id INT DEFAULT NULL, heure TIME DEFAULT NULL, jour DATE NOT NULL, INDEX IDX_39B7118FE4356E99 (poubelles_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poubelles (id INT AUTO_INCREMENT NOT NULL, contenue_id INT DEFAULT NULL, couleur_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, INDEX IDX_54640917715CA2B0 (contenue_id), INDEX IDX_54640917C31BA576 (couleur_id), INDEX IDX_54640917A73F0036 (ville_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE horaires ADD CONSTRAINT FK_39B7118FE4356E99 FOREIGN KEY (poubelles_id) REFERENCES poubelles (id)');
        $this->addSql('ALTER TABLE poubelles ADD CONSTRAINT FK_54640917715CA2B0 FOREIGN KEY (contenue_id) REFERENCES contenu (id)');
        $this->addSql('ALTER TABLE poubelles ADD CONSTRAINT FK_54640917C31BA576 FOREIGN KEY (couleur_id) REFERENCES couleurs (id)');
        $this->addSql('ALTER TABLE poubelles ADD CONSTRAINT FK_54640917A73F0036 FOREIGN KEY (ville_id) REFERENCES villes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE horaires DROP FOREIGN KEY FK_39B7118FE4356E99');
        $this->addSql('DROP TABLE horaires');
        $this->addSql('DROP TABLE poubelles');
    }
}
