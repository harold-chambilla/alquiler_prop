<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240929045356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recibo_detalle_consumo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recibo_id INTEGER NOT NULL, rdc_consumo NUMERIC(16, 2) DEFAULT NULL, rdc_tipo INTEGER DEFAULT NULL, rdc_subtotal NUMERIC(16, 2) DEFAULT NULL, rdc_estado BOOLEAN DEFAULT NULL, lect_ant_id INTEGER NOT NULL, lec_act_id INTEGER NOT NULL, CONSTRAINT FK_E68D62492C458692 FOREIGN KEY (recibo_id) REFERENCES recibo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E68D62492C458692 ON recibo_detalle_consumo (recibo_id)');
        $this->addSql('DROP TABLE detalle_consumo_luz');
        $this->addSql('CREATE TEMPORARY TABLE __temp__medidor AS SELECT id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado FROM medidor');
        $this->addSql('DROP TABLE medidor');
        $this->addSql('CREATE TABLE medidor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piso_id INTEGER NOT NULL, mel_codigo VARCHAR(64) DEFAULT NULL, mel_tipo VARCHAR(64) DEFAULT NULL, mel_marca VARCHAR(64) DEFAULT NULL, mel_año VARCHAR(16) DEFAULT NULL, mel_fecha_compra DATETIME DEFAULT NULL, mel_fecha_instalacion DATETIME DEFAULT NULL, mel_fecha_desinstalacion DATETIME DEFAULT NULL, mel_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_161801321AC830AF FOREIGN KEY (piso_id) REFERENCES piso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO medidor (id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado) SELECT id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado FROM __temp__medidor');
        $this->addSql('DROP TABLE __temp__medidor');
        $this->addSql('CREATE INDEX IDX_161801321AC830AF ON medidor (piso_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE detalle_consumo_luz (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, lectura_anterior_id_id INTEGER DEFAULT NULL, lectura_actual_id_id INTEGER DEFAULT NULL, recibo_id_id INTEGER DEFAULT NULL, dcl_consumo NUMERIC(16, 2) DEFAULT NULL, dcl_tipo VARCHAR(64) DEFAULT NULL COLLATE "BINARY", dcl_subtotal NUMERIC(16, 2) DEFAULT NULL, dcl_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_595F04ABF7CF212E FOREIGN KEY (lectura_anterior_id_id) REFERENCES lectura (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_595F04ABA36312A0 FOREIGN KEY (lectura_actual_id_id) REFERENCES lectura (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_595F04ABF3B92350 FOREIGN KEY (recibo_id_id) REFERENCES recibo (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_595F04ABF3B92350 ON detalle_consumo_luz (recibo_id_id)');
        $this->addSql('CREATE INDEX IDX_595F04ABA36312A0 ON detalle_consumo_luz (lectura_actual_id_id)');
        $this->addSql('CREATE INDEX IDX_595F04ABF7CF212E ON detalle_consumo_luz (lectura_anterior_id_id)');
        $this->addSql('DROP TABLE recibo_detalle_consumo');
        $this->addSql('CREATE TEMPORARY TABLE __temp__medidor AS SELECT id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado FROM medidor');
        $this->addSql('DROP TABLE medidor');
        $this->addSql('CREATE TABLE medidor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, mel_codigo VARCHAR(64) DEFAULT NULL, mel_tipo VARCHAR(64) DEFAULT NULL, mel_marca VARCHAR(64) DEFAULT NULL, mel_año VARCHAR(16) DEFAULT NULL, mel_fecha_compra DATETIME DEFAULT NULL, mel_fecha_instalacion DATETIME DEFAULT NULL, mel_fecha_desinstalacion DATETIME DEFAULT NULL, mel_estado BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO medidor (id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado) SELECT id, mel_codigo, mel_tipo, mel_marca, mel_año, mel_fecha_compra, mel_fecha_instalacion, mel_fecha_desinstalacion, mel_estado FROM __temp__medidor');
        $this->addSql('DROP TABLE __temp__medidor');
    }
}
