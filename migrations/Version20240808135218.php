<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808135218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contrato ADD COLUMN co_alquiler_mensual NUMERIC(16, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE contrato ADD COLUMN co_agua NUMERIC(16, 2) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__contrato AS SELECT id, arrendatario_id_id, usuario_id_id, residencia_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento FROM contrato');
        $this->addSql('DROP TABLE contrato');
        $this->addSql('CREATE TABLE contrato (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, arrendatario_id_id INTEGER DEFAULT NULL, usuario_id_id INTEGER DEFAULT NULL, residencia_id_id INTEGER DEFAULT NULL, piso_id_id INTEGER DEFAULT NULL, co_fecha_ingreso DATETIME DEFAULT NULL, co_fecha_vencimiento DATE DEFAULT NULL, CONSTRAINT FK_6669652394E139C4 FOREIGN KEY (arrendatario_id_id) REFERENCES arrendatario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523629AF449 FOREIGN KEY (usuario_id_id) REFERENCES usuario (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523DC60AB69 FOREIGN KEY (residencia_id_id) REFERENCES residencia (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_66696523FE9CA306 FOREIGN KEY (piso_id_id) REFERENCES piso (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO contrato (id, arrendatario_id_id, usuario_id_id, residencia_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento) SELECT id, arrendatario_id_id, usuario_id_id, residencia_id_id, piso_id_id, co_fecha_ingreso, co_fecha_vencimiento FROM __temp__contrato');
        $this->addSql('DROP TABLE __temp__contrato');
        $this->addSql('CREATE INDEX IDX_6669652394E139C4 ON contrato (arrendatario_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523629AF449 ON contrato (usuario_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523DC60AB69 ON contrato (residencia_id_id)');
        $this->addSql('CREATE INDEX IDX_66696523FE9CA306 ON contrato (piso_id_id)');
    }
}
