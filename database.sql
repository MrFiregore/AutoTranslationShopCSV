CREATE TABLE firegore_cadena
(
    id                  INT AUTO_INCREMENT NOT NULL,
    id_idioma           INT DEFAULT NULL,
    nombre              TEXT               NOT NULL,
    fecha_creacion      DATETIME           NOT NULL,
    fecha_actualizacion DATETIME           NOT NULL,
    INDEX IDX_58C510CB3BFFEBE1 (id_idioma),
    UNIQUE INDEX firegore_cadena_unique (nombre(767), id_idioma),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE firegore_categoria
(
    id                  INT AUTO_INCREMENT NOT NULL,
    id_cadena           INT                NOT NULL,
    fecha_creacion      DATETIME           NOT NULL,
    fecha_actualizacion DATETIME           NOT NULL,
    INDEX IDX_909930178654493F (id_cadena),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE firegore_idioma
(
    id                  INT AUTO_INCREMENT NOT NULL,
    nombre_corto        VARCHAR(64)  DEFAULT NULL,
    nombre              VARCHAR(255) DEFAULT NULL,
    fecha_creacion      DATETIME           NOT NULL,
    fecha_actualizacion DATETIME           NOT NULL,
    UNIQUE INDEX UNIQ_E56EB21599C9E425 (nombre_corto),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE firegore_producto
(
    id                    INT AUTO_INCREMENT           NOT NULL,
    id_categoria          INT              DEFAULT NULL,
    id_nombre_cadena      INT                          NOT NULL,
    id_descripcion_cadena INT                          NOT NULL,
    precio                DOUBLE PRECISION DEFAULT '0' NOT NULL,
    stock                 INT              DEFAULT 0   NOT NULL,
    fecha_ultima_venta    DATETIME         DEFAULT NULL,
    fecha_creacion        DATETIME                     NOT NULL,
    fecha_actualizacion   DATETIME                     NOT NULL,
    INDEX IDX_9507F9F8CE25AE0A (id_categoria),
    INDEX IDX_9507F9F8D3BF3CA1 (id_nombre_cadena),
    INDEX IDX_9507F9F8EA9230BD (id_descripcion_cadena),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
CREATE TABLE firegore_traduccion
(
    id                  INT AUTO_INCREMENT NOT NULL,
    id_cadena           INT                NOT NULL,
    id_cadena_traducida INT                NOT NULL,
    fecha_creacion      DATETIME           NOT NULL,
    fecha_actualizacion DATETIME           NOT NULL,
    INDEX IDX_C4903C1E8654493F (id_cadena),
    INDEX IDX_C4903C1EE3EBCD17 (id_cadena_traducida),
    PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8
  COLLATE utf8_unicode_ci
  ENGINE = InnoDB;
ALTER TABLE firegore_cadena
    ADD CONSTRAINT FK_58C510CB3BFFEBE1 FOREIGN KEY (id_idioma) REFERENCES firegore_idioma (id);
ALTER TABLE firegore_categoria
    ADD CONSTRAINT FK_909930178654493F FOREIGN KEY (id_cadena) REFERENCES firegore_cadena (id) ON DELETE CASCADE;
ALTER TABLE firegore_producto
    ADD CONSTRAINT FK_9507F9F8CE25AE0A FOREIGN KEY (id_categoria) REFERENCES firegore_categoria (id) ON DELETE CASCADE;
ALTER TABLE firegore_producto
    ADD CONSTRAINT FK_9507F9F8D3BF3CA1 FOREIGN KEY (id_nombre_cadena) REFERENCES firegore_cadena (id) ON DELETE CASCADE;
ALTER TABLE firegore_producto
    ADD CONSTRAINT FK_9507F9F8EA9230BD FOREIGN KEY (id_descripcion_cadena) REFERENCES firegore_cadena (id) ON DELETE CASCADE;
ALTER TABLE firegore_traduccion
    ADD CONSTRAINT FK_C4903C1E8654493F FOREIGN KEY (id_cadena) REFERENCES firegore_cadena (id) ON DELETE CASCADE;
ALTER TABLE firegore_traduccion
    ADD CONSTRAINT FK_C4903C1EE3EBCD17 FOREIGN KEY (id_cadena_traducida) REFERENCES firegore_cadena (id) ON DELETE CASCADE;