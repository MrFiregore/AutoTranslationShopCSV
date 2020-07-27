<?php
    
    use Doctrine\Common\Annotations\AnnotationReader;
    use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
    use Doctrine\ORM\Tools\Setup;
    use Doctrine\ORM\EntityManager;
    use Doctrine\Common\Cache\ArrayCache;
    use Doctrine\Common\Annotations\AnnotationRegistry;
    
    
    $entities_path = [__FIREGORE__ . "src" . DIRECTORY_SEPARATOR . "Model"];
    $config        = Setup::createAnnotationMetadataConfiguration($entities_path, true, null, null, false);
    
    $cache = new ArrayCache();
    $config->setMetadataCacheImpl($cache);
    $config->setQueryCacheImpl($cache);
    $config->addEntityNamespace('', 'firegore\AutoTranslationShopCSV\Model');
    
    // Tambien se puede usar SQLite para modo pruebas
//    $conn = [
//        'driver' => 'pdo_sqlite',
//        'path'   => __FIREGORE__ . 'config/db.sqlite',
//    ];
    $conn  = [
        'dbname'        => $_ENV["DB_NAME"],
        'user'          => $_ENV["DB_USER"],
        'password'      => $_ENV["DB_PASSWORD"],
        'port'          => $_ENV["DB_PORT"],
        'host'          => $_ENV["DB_HOST"],
        'driver'        => 'pdo_mysql',
        'charset'       => 'utf8',
        'driverOptions' => [
            1002 => 'SET NAMES utf8',
        ],
    ];
    
    
    $driver = new AnnotationDriver(new AnnotationReader(), $entities_path);
    AnnotationRegistry::registerLoader('class_exists');
    
    $config->setMetadataDriverImpl($driver);
    $entity_manager = EntityManager::create($conn, $config);
    $platform       = $entity_manager->getConnection()->getDatabasePlatform();
    $platform->registerDoctrineTypeMapping('bit', 'boolean');
    \firegore\AutoTranslationShopCSV\Database::getInstance()->setEntityManager($entity_manager);

