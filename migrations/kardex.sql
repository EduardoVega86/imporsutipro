CREATE TABLE `kardex_tipo_movimiento`
(
    `id_tipo` int NOT NULL AUTO_INCREMENT,
    `tipo`    varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    PRIMARY KEY (`id_tipo`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;

CREATE TABLE `kardex`
(
    `id_kardex`              bigint unsigned NOT NULL AUTO_INCREMENT,
    `id_inventario`          bigint unsigned NOT NULL,
    `id_tipo_movimiento`     int unsigned    NOT NULL,
    `cantidad`               bigint unsigned NOT NULL,
    `fecha`                  timestamp       NOT NULL,
    `descripcion`            text,
    `stock_previo`           bigint unsigned NOT NULL,
    `stock_actual`           bigint unsigned NOT NULL,
    `id_plataforma`          bigint unsigned NOT NULL,
    `id_usuario`             bigint unsigned NOT NULL,
    `plataforma_responsable` bigint unsigned NOT NULL,
    PRIMARY KEY (`id_kardex`),
    FOREIGN KEY (`id_inventario`) REFERENCES `inventario_bodegas` (`id_inventario`),
    FOREIGN KEY (`id_tipo_movimiento`) REFERENCES `kardex_tipo_movimiento` (`id_tipo`),
    FOREIGN KEY (`id_plataforma`) REFERENCES `plataformas` (`id_plataforma`),
    FOREIGN KEY (`id_usuario`) REFERENCES `users` (`id_users`),
    FOREIGN KEY (`plataforma_responsable`) REFERENCES `plataformas` (`id_plataforma`)
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4
  COLLATE = utf8mb4_unicode_ci;