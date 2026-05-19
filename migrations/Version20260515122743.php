<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260515122743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL, expires_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD numero_commande INT DEFAULT NULL, ADD date_commande DATE DEFAULT NULL, ADD date_pretation DATE DEFAULT NULL, ADD prix_menu DOUBLE PRECISION DEFAULT NULL, ADD nb_pers INT DEFAULT NULL, ADD prix_livraison DOUBLE PRECISION DEFAULT NULL, ADD pret_materiel LONGBLOB DEFAULT NULL, ADD resti_materiel LONGBLOB DEFAULT NULL, DROP numeroCommande, DROP dateCommande, DROP datePretation, DROP prixMenu, DROP nbPers, DROP prixLivraison, DROP pretMateriel, DROP restiMateriel, CHANGE heureLivraison heure_livraison VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE commande ADD numeroCommande INT DEFAULT NULL, ADD dateCommande DATE DEFAULT NULL, ADD datePretation DATE DEFAULT NULL, ADD prixMenu DOUBLE PRECISION DEFAULT NULL, ADD nbPers INT DEFAULT NULL, ADD prixLivraison DOUBLE PRECISION DEFAULT NULL, ADD pretMateriel LONGBLOB DEFAULT NULL, ADD restiMateriel LONGBLOB DEFAULT NULL, DROP numero_commande, DROP date_commande, DROP date_pretation, DROP prix_menu, DROP nb_pers, DROP prix_livraison, DROP pret_materiel, DROP resti_materiel, CHANGE heure_livraison heureLivraison VARCHAR(255) DEFAULT NULL');
    }
}
