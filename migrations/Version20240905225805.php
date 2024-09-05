<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240905225805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__detalle_consumo_luz AS SELECT id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado FROM detalle_consumo_luz');
        $this->addSql('DROP TABLE detalle_consumo_luz');
        $this->addSql('CREATE TABLE detalle_consumo_luz (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lectura_anterior_id_id INTEGER DEFAULT NULL, lectura_actual_id_id INTEGER DEFAULT NULL, recibo_id_id INTEGER DEFAULT NULL, dcl_consumo NUMERIC(16, 2) DEFAULT NULL, dcl_tipo VARCHAR(64) DEFAULT NULL, dcl_subtotal NUMERIC(16, 2) DEFAULT NULL, dcl_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_595F04ABF7CF212E FOREIGN KEY (lectura_anterior_id_id) REFERENCES lectura (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_595F04ABA36312A0 FOREIGN KEY (lectura_actual_id_id) REFERENCES lectura (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_595F04ABF3B92350 FOREIGN KEY (recibo_id_id) REFERENCES recibo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO detalle_consumo_luz (id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado) SELECT id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado FROM __temp__detalle_consumo_luz');
        $this->addSql('DROP TABLE __temp__detalle_consumo_luz');
        $this->addSql('CREATE INDEX IDX_595F04ABA36312A0 ON detalle_consumo_luz (lectura_actual_id_id)');
        $this->addSql('CREATE INDEX IDX_595F04ABF7CF212E ON detalle_consumo_luz (lectura_anterior_id_id)');
        $this->addSql('CREATE INDEX IDX_595F04ABF3B92350 ON detalle_consumo_luz (recibo_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__detalle_consumo_luz AS SELECT id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado FROM detalle_consumo_luz');
        $this->addSql('DROP TABLE detalle_consumo_luz');
        $this->addSql('CREATE TABLE detalle_consumo_luz (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lectura_anterior_id_id INTEGER DEFAULT NULL, lectura_actual_id_id INTEGER DEFAULT NULL, dcl_consumo NUMERIC(16, 2) DEFAULT NULL, dcl_tipo VARCHAR(64) DEFAULT NULL, dcl_subtotal NUMERIC(16, 2) DEFAULT NULL, dcl_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_595F04ABF7CF212E FOREIGN KEY (lectura_anterior_id_id) REFERENCES lectura (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_595F04ABA36312A0 FOREIGN KEY (lectura_actual_id_id) REFERENCES lectura (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO detalle_consumo_luz (id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado) SELECT id, lectura_anterior_id_id, lectura_actual_id_id, dcl_consumo, dcl_tipo, dcl_subtotal, dcl_estado FROM __temp__detalle_consumo_luz');
        $this->addSql('DROP TABLE __temp__detalle_consumo_luz');
        $this->addSql('CREATE INDEX IDX_595F04ABF7CF212E ON detalle_consumo_luz (lectura_anterior_id_id)');
        $this->addSql('CREATE INDEX IDX_595F04ABA36312A0 ON detalle_consumo_luz (lectura_actual_id_id)');
    }
}
