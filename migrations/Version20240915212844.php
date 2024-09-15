<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240915212844 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__arrendatario AS SELECT id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado FROM arrendatario');
        $this->addSql('DROP TABLE arrendatario');
        $this->addSql('CREATE TABLE arrendatario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ao_nombres VARCHAR(64) DEFAULT NULL, ao_apellidos VARCHAR(64) DEFAULT NULL, ao_telefono VARCHAR(16) DEFAULT NULL, ao_tipo VARCHAR(64) DEFAULT NULL, ao_cedula_identidad VARCHAR(16) NOT NULL, ao_fecha_nacimiento DATE DEFAULT NULL, ao_foto_dni CLOB DEFAULT NULL, ao_foto CLOB DEFAULT NULL, ao_estado BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO arrendatario (id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado) SELECT id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado FROM __temp__arrendatario');
        $this->addSql('DROP TABLE __temp__arrendatario');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429B5C84D31FFC58 ON arrendatario (ao_telefono)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429B5C84204A3E68 ON arrendatario (ao_cedula_identidad)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__contrato AS SELECT id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado FROM contrato');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('CREATE TABLE contrato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, arrendatario_id_id INTEGER DEFAULT NULL, piso_id_id INTEGER DEFAULT NULL, co_fecha_ingreso DATETIME DEFAULT NULL, co_fecha_vencimiento DATETIME DEFAULT NULL, co_alquiler_mensual NUMERIC(16, 2) DEFAULT NULL, co_agua NUMERIC(16, 2) DEFAULT NULL, co_fecha_actual DATETIME DEFAULT NULL, co_estado BOOLEAN NOT NULL, CONSTRAINT FK_6669652394E139C4 FOREIGN KEY (arrendatario_id_id) REFERENCES arrendatario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523FE9CA306 FOREIGN KEY (piso_id_id) REFERENCES piso (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contrato (id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado) SELECT id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado FROM __temp__contrato');
        $this->addSql('DROP TABLE __temp__contrato');
        $this->addSql('CREATE INDEX IDX_66696523FE9CA306 ON contrato (piso_id_id)');
        $this->addSql('CREATE INDEX IDX_6669652394E139C4 ON contrato (arrendatario_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__residencia AS SELECT id, res_direccion FROM residencia');
        $this->addSql('DROP TABLE residencia');
        $this->addSql('CREATE TABLE residencia (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER NOT NULL, res_direccion VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_E666A765DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO residencia (id, res_direccion) SELECT id, res_direccion FROM __temp__residencia');
        $this->addSql('DROP TABLE __temp__residencia');
        $this->addSql('CREATE INDEX IDX_E666A765DB38439E ON residencia (usuario_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__arrendatario AS SELECT id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado FROM arrendatario');
        $this->addSql('DROP TABLE arrendatario');
        $this->addSql('CREATE TABLE arrendatario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ao_nombres VARCHAR(64) DEFAULT NULL, ao_apellidos VARCHAR(64) DEFAULT NULL, ao_telefono VARCHAR(16) DEFAULT NULL, ao_tipo VARCHAR(64) DEFAULT NULL, ao_cedula_identidad VARCHAR(16) NOT NULL, ao_fecha_nacimiento DATE DEFAULT NULL, ao_foto_dni CLOB DEFAULT NULL, ao_foto CLOB DEFAULT NULL, ao_estado BOOLEAN DEFAULT NULL)');
        $this->addSql('INSERT INTO arrendatario (id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado) SELECT id, ao_nombres, ao_apellidos, ao_telefono, ao_tipo, ao_cedula_identidad, ao_fecha_nacimiento, ao_foto_dni, ao_foto, ao_estado FROM __temp__arrendatario');
        $this->addSql('DROP TABLE __temp__arrendatario');
        $this->addSql('CREATE TEMPORARY TABLE __temp__contrato AS SELECT id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado FROM contrato');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('CREATE TABLE contrato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, arrendatario_id_id INTEGER DEFAULT NULL, piso_id_id INTEGER DEFAULT NULL, usuario_id_id INTEGER DEFAULT NULL, residencia_id_id INTEGER DEFAULT NULL, co_fecha_ingreso DATETIME DEFAULT NULL, co_fecha_vencimiento DATE DEFAULT NULL, co_alquiler_mensual NUMERIC(16, 2) DEFAULT NULL, co_agua NUMERIC(16, 2) DEFAULT NULL, co_fecha_actual DATETIME DEFAULT NULL, co_estado BOOLEAN NOT NULL, CONSTRAINT FK_6669652394E139C4 FOREIGN KEY (arrendatario_id_id) REFERENCES arrendatario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523FE9CA306 FOREIGN KEY (piso_id_id) REFERENCES piso (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523DC60AB69 FOREIGN KEY (residencia_id_id) REFERENCES residencia (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contrato (id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado) SELECT id, arrendatario_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento, co_alquiler_mensual, co_agua, co_fecha_actual, co_estado FROM __temp__contrato');
        $this->addSql('DROP TABLE __temp__contrato');
        $this->addSql('CREATE INDEX IDX_6669652394E139C4 ON contrato (arrendatario_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523FE9CA306 ON contrato (piso_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523DC60AB69 ON contrato (residencia_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523629AF449 ON contrato (usuario_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__residencia AS SELECT id, res_direccion FROM residencia');
        $this->addSql('DROP TABLE residencia');
        $this->addSql('CREATE TABLE residencia (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, res_direccion VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO residencia (id, res_direccion) SELECT id, res_direccion FROM __temp__residencia');
        $this->addSql('DROP TABLE __temp__residencia');
    }
}
