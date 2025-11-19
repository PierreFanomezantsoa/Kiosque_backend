<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118084011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE depense_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE budget_mensuel_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE membre_famille_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE categorie_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE revenue_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE publication_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE publication (id INT NOT NULL, nom VARCHAR(120) DEFAULT NULL, description VARCHAR(200) NOT NULL, prix INT DEFAULT NULL, prix_promo INT NOT NULL, image VARCHAR(200) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense DROP CONSTRAINT fk_34059757fd4ca4f4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense DROP CONSTRAINT fk_34059757c33f2eba
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense DROP CONSTRAINT fk_34059757655ed967
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue DROP CONSTRAINT fk_e9116c8512066a9a
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue DROP CONSTRAINT fk_e9116c85cd1dc19c
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue DROP CONSTRAINT fk_e9116c85e6ada943
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE membre_famille
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE depense
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE revenue
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE budget_mensuel
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE publication_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE depense_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE budget_mensuel_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE membre_famille_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE categorie_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE revenue_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE membre_famille (id INT NOT NULL, ref_fam INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(150) NOT NULL, email VARCHAR(150) NOT NULL, relation VARCHAR(30) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE depense (id INT NOT NULL, budget_mensuel_id INT DEFAULT NULL, cat_id_id INT DEFAULT NULL, membrefamille_id INT DEFAULT NULL, ref_dep INT NOT NULL, description VARCHAR(255) NOT NULL, date_dep DATE NOT NULL, montant_depense DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_34059757655ed967 ON depense (membrefamille_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_34059757c33f2eba ON depense (cat_id_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_34059757fd4ca4f4 ON depense (budget_mensuel_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE revenue (id INT NOT NULL, budge_mensuel_id INT DEFAULT NULL, membre_famille_id INT DEFAULT NULL, cat_id INT DEFAULT NULL, date_revenue DATE NOT NULL, descrption_rev VARCHAR(150) NOT NULL, source VARCHAR(100) NOT NULL, montant_revenue DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e9116c85e6ada943 ON revenue (cat_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e9116c85cd1dc19c ON revenue (membre_famille_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_e9116c8512066a9a ON revenue (budge_mensuel_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id INT NOT NULL, nom_cat VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, description_cat VARCHAR(100) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE budget_mensuel (id INT NOT NULL, mois VARCHAR(50) NOT NULL, annee VARCHAR(25) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense ADD CONSTRAINT fk_34059757fd4ca4f4 FOREIGN KEY (budget_mensuel_id) REFERENCES budget_mensuel (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense ADD CONSTRAINT fk_34059757c33f2eba FOREIGN KEY (cat_id_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE depense ADD CONSTRAINT fk_34059757655ed967 FOREIGN KEY (membrefamille_id) REFERENCES membre_famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue ADD CONSTRAINT fk_e9116c8512066a9a FOREIGN KEY (budge_mensuel_id) REFERENCES budget_mensuel (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue ADD CONSTRAINT fk_e9116c85cd1dc19c FOREIGN KEY (membre_famille_id) REFERENCES membre_famille (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE revenue ADD CONSTRAINT fk_e9116c85e6ada943 FOREIGN KEY (cat_id) REFERENCES categorie (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE publication
        SQL);
    }
}
