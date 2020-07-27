<?php
    
    use firegore\AutoTranslationShopCSV\CSVReader;
    use firegore\AutoTranslationShopCSV\Translator\Codes;
    use firegore\AutoTranslationShopCSV\Database;
    
    require_once "vendor/autoload.php";
    require_once "config/bootstrap.php";
    
    
    //Añadimos los idiomas a la base de datos (se añaden solo si no existen)
    Database::getInstance()->getLanguage(null, "Spanish");
    Database::getInstance()->getLanguage(Codes::FR_FR, "French");
    Database::getInstance()->getLanguage(Codes::EN, "English");
    
    
    //Leemos y añadimos los datos del dichero a la bbdd
    (new CSVReader($_ENV["CSV"]));
    
    //Auto traducción de todas las palabras que tenemos en la base de datos
    if (!!(intval($_ENV["AUTO_TRANSLATE"]))) Database::getInstance()->autoTranslateAll();
    
    // CONSULTAS SQL
    $categoria = "camisetas";
    $idioma    = constant(Codes::class . "::" . strtoupper(locale_get_default()));
    /**
     * Esta sería la consulta ideal para obtener todo los productos con categoria, nombre y descripción traducidas
     * Lamentablemente en PHP no se puede usar WITH
     */
    $sql = ";WITH cte AS   (SELECT COALESCE(bc.id ,bct.id) as id, bct.nombre as nombre
                FROM firegore_cadena bc
                         RIGHT JOIN firegore_traduccion bt on bc.id = bt.id_cadena
                         RIGHT JOIN firegore_cadena bct on bct.id = bt.id_cadena_traducida
                         INNER JOIN firegore_idioma bi on bct.id_idioma = bi.id
                WHERE bi.nombre_corto = \"es-ES\"
)
 SELECT bp.id as id, tmpc.nombre as categoria,tmpn.nombre as nombre, tmpd.nombre as descripcion, bp.stock, bp.precio, bp.fecha_ultima_venta
 FROM firegore_producto bp
          INNER JOIN firegore_categoria b on bp.id_categoria = b.id
          INNER JOIN cte AS tmpn ON tmpn.id = bp.id_nombre_cadena
          INNER JOIN cte AS tmpc ON tmpc.id = b.id_cadena

          INNER JOIN cte AS tmpd ON tmpd.id = bp.id_descripcion_cadena
;
SET @traduccion :=(SELECT bc.id as id, bct.nombre as nombre
                    FROM firegore_cadena bc
                             INNER JOIN firegore_traduccion bt on bc.id = bt.id_cadena
                             INNER JOIN firegore_cadena bct on bct.id = bt.id_cadena_traducida
                             INNER JOIN firegore_idioma bi on bct.id_idioma = bi.id
                    WHERE bi.nombre_corto = \"fr-FR\"
);";
    $sql = "SELECT bp.id as id, tmpc.nombre as categoria,tmpn.nombre as nombre, tmpd.nombre as descripcion, bp.stock, bp.precio, bp.fecha_ultima_venta
FROM firegore_producto bp
    INNER JOIN firegore_categoria b on bp.id_categoria = b.id
    INNER JOIN
     (SELECT COALESCE(bc.id ,bct.id) as id, bct.nombre as nombre
FROM firegore_cadena bc
         RIGHT JOIN firegore_traduccion bt on bc.id = bt.id_cadena
         RIGHT JOIN firegore_cadena bct on bct.id = bt.id_cadena_traducida
         INNER JOIN firegore_idioma bi on bct.id_idioma = bi.id

      WHERE bi.nombre_corto = :idioma
     ) AS tmpn ON tmpn.id = bp.id_nombre_cadena
    INNER JOIN
     (SELECT COALESCE(bc.id ,bct.id) as id, bct.nombre as nombre
FROM firegore_cadena bc
         RIGHT JOIN firegore_traduccion bt on bc.id = bt.id_cadena
         RIGHT JOIN firegore_cadena bct on bct.id = bt.id_cadena_traducida
         INNER JOIN firegore_idioma bi on bct.id_idioma = bi.id

      WHERE bi.nombre_corto = :idioma
     ) AS tmpc ON tmpc.id = b.id_cadena

    INNER JOIN
     (SELECT COALESCE(bc.id ,bct.id) as id, bct.nombre as nombre
FROM firegore_cadena bc
         RIGHT JOIN firegore_traduccion bt on bc.id = bt.id_cadena
         RIGHT JOIN firegore_cadena bct on bct.id = bt.id_cadena_traducida
         INNER JOIN firegore_idioma bi on bct.id_idioma = bi.id

      WHERE bi.nombre_corto = :idioma
     ) AS tmpd ON tmpd.id = bp.id_descripcion_cadena
WHERE bp.id_categoria = (SELECT bc2.id
                         FROM firegore_categoria bc2
                                  INNER JOIN firegore_cadena b2 on bc2.id_cadena = b2.id
                                  INNER JOIN firegore_traduccion bt2 on b2.id = bt2.id_cadena
                                  INNER JOIN firegore_cadena bct2 ON bct2.id = bt2.id_cadena_traducida
                         WHERE b2.nombre LIKE :categoria OR bct2.nombre LIKE :categoria LIMIT 1)

;";
    //    $sql = "SELECT * FROM firegore_cadena bc WHERE bc.id = :id";
    /**
     * @var \Doctrine\ORM\EntityManager $entity_manager
     */
    $query = $entity_manager->getConnection()->prepare($sql);
    
    $query->execute(["idioma" => $idioma, "categoria" => $categoria]);
    +!d($query->fetchAll(), $idioma);
