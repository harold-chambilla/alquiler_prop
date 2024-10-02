<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240930135731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arrendatario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, ao_nombres VARCHAR(64) DEFAULT NULL, ao_apellidos VARCHAR(64) DEFAULT NULL, ao_telefono VARCHAR(16) DEFAULT NULL, ao_tipo VARCHAR(64) DEFAULT NULL, ao_cedula_identidad VARCHAR(16) NOT NULL, ao_fecha_nacimiento DATE DEFAULT NULL, ao_foto_dni CLOB DEFAULT NULL, ao_foto CLOB DEFAULT NULL, ao_estado BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429B5C84D31FFC58 ON arrendatario (ao_telefono)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_429B5C84204A3E68 ON arrendatario (ao_cedula_identidad)');
        $this->addSql('CREATE TABLE concepto_pago (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cop_nombre VARCHAR(64) DEFAULT NULL, cop_descripcion CLOB DEFAULT NULL, cop_precio NUMERIC(16, 2) DEFAULT NULL, cop_estado BOOLEAN DEFAULT NULL)');
        $this->addSql('CREATE TABLE contrato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, arrendatario_id_id INTEGER DEFAULT NULL, piso_id_id INTEGER DEFAULT NULL, co_fecha_ingreso DATETIME DEFAULT NULL, co_fecha_vencimiento DATETIME DEFAULT NULL, co_alquiler_mensual NUMERIC(16, 2) DEFAULT NULL, co_agua NUMERIC(16, 2) DEFAULT NULL, co_fecha_actual DATETIME DEFAULT NULL, co_estado BOOLEAN NOT NULL, CONSTRAINT FK_6669652394E139C4 FOREIGN KEY (arrendatario_id_id) REFERENCES arrendatario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523FE9CA306 FOREIGN KEY (piso_id_id) REFERENCES piso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6669652394E139C4 ON contrato (arrendatario_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523FE9CA306 ON contrato (piso_id_id)');
        $this->addSql('CREATE TABLE lectura (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, medidor_id_id INTEGER DEFAULT NULL, lel_dato NUMERIC(16, 2) DEFAULT NULL, lel_fecha DATETIME DEFAULT NULL, lel_estado BOOLEAN DEFAULT NULL, lel_tipo VARCHAR(16) DEFAULT NULL, CONSTRAINT FK_C60ABD51CA85D6F FOREIGN KEY (medidor_id_id) REFERENCES medidor (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_C60ABD51CA85D6F ON lectura (medidor_id_id)');
        $this->addSql('CREATE TABLE medidor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, piso_id INTEGER NOT NULL, mel_codigo VARCHAR(64) DEFAULT NULL, mel_tipo VARCHAR(64) DEFAULT NULL, mel_marca VARCHAR(64) DEFAULT NULL, mel_año VARCHAR(16) DEFAULT NULL, mel_fecha_compra DATETIME DEFAULT NULL, mel_fecha_instalacion DATETIME DEFAULT NULL, mel_fecha_desinstalacion DATETIME DEFAULT NULL, mel_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_161801321AC830AF FOREIGN KEY (piso_id) REFERENCES piso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_161801321AC830AF ON medidor (piso_id)');
        $this->addSql('CREATE TABLE piso (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, residencia_id_id INTEGER DEFAULT NULL, pi_posicion VARCHAR(16) DEFAULT NULL, pi_cuarto VARCHAR(16) DEFAULT NULL, pi_zona VARCHAR(16) DEFAULT NULL, pi_estado BOOLEAN DEFAULT NULL, CONSTRAINT FK_D462D9D3DC60AB69 FOREIGN KEY (residencia_id_id) REFERENCES residencia (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D462D9D3DC60AB69 ON piso (residencia_id_id)');
        $this->addSql('CREATE TABLE recibo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contrato_id_id INTEGER DEFAULT NULL, re_codigo VARCHAR(64) DEFAULT NULL, re_fecha_emision DATETIME DEFAULT NULL, re_estado BOOLEAN DEFAULT NULL, re_pago_total NUMERIC(16, 2) DEFAULT NULL, CONSTRAINT FK_42A928FA2FE2F275 FOREIGN KEY (contrato_id_id) REFERENCES contrato (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_42A928FA2FE2F275 ON recibo (contrato_id_id)');
        $this->addSql('CREATE TABLE recibo_concepto_pago (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recibo_id_id INTEGER DEFAULT NULL, concepto_pago_id_id INTEGER DEFAULT NULL, rcp_fecha_digitacion DATETIME DEFAULT NULL, CONSTRAINT FK_7C7DB5FFF3B92350 FOREIGN KEY (recibo_id_id) REFERENCES recibo (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_7C7DB5FF2F0AFD32 FOREIGN KEY (concepto_pago_id_id) REFERENCES concepto_pago (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_7C7DB5FFF3B92350 ON recibo_concepto_pago (recibo_id_id)');
        $this->addSql('CREATE INDEX IDX_7C7DB5FF2F0AFD32 ON recibo_concepto_pago (concepto_pago_id_id)');
        $this->addSql('CREATE TABLE recibo_detalle_consumo (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, recibo_id INTEGER NOT NULL, rdc_consumo NUMERIC(16, 2) DEFAULT NULL, rdc_tipo INTEGER DEFAULT NULL, rdc_subtotal NUMERIC(16, 2) DEFAULT NULL, rdc_estado BOOLEAN DEFAULT NULL, lect_ant_id INTEGER NOT NULL, lec_act_id INTEGER NOT NULL, CONSTRAINT FK_E68D62492C458692 FOREIGN KEY (recibo_id) REFERENCES recibo (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E68D62492C458692 ON recibo_detalle_consumo (recibo_id)');
        $this->addSql('CREATE TABLE residencia (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, usuario_id INTEGER NOT NULL, res_direccion VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_E666A765DB38439E FOREIGN KEY (usuario_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E666A765DB38439E ON residencia (usuario_id)');
        $this->addSql('CREATE TABLE usuario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON usuario (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE arrendatario');
        $this->addSql('DROP TABLE concepto_pago');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('DROP TABLE lectura');
        $this->addSql('DROP TABLE medidor');
        $this->addSql('DROP TABLE piso');
        $this->addSql('DROP TABLE recibo');
        $this->addSql('DROP TABLE recibo_concepto_pago');
        $this->addSql('DROP TABLE recibo_detalle_consumo');
        $this->addSql('DROP TABLE residencia');
        $this->addSql('DROP TABLE usuario');
    }
}
