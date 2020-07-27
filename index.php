<?php
    
    use firegore\AutoTranslationShopCSV\CSVReader;
    use firegore\AutoTranslationShopCSV\Translator\Codes;
    use firegore\AutoTranslationShopCSV\Database;
    
    require_once "vendor/autoload.php";
    require_once "config/bootstrap.php";
    
    
    /**
     * Add the languages into the DB (only stored if not exist)
     */
    Database::getInstance()->getLanguage(null, "Spanish");
    Database::getInstance()->getLanguage(Codes::FR_FR, "French");
    Database::getInstance()->getLanguage(Codes::EN, "English");
    
    
    /**
     * Read and store the data of the file in de DB
     */
    (new CSVReader($_ENV["CSV"]));
    
    /**
     * Auto translation of all the words we have in the database 983358296 e
     */
    if (!!(intval($_ENV["AUTO_TRANSLATE"]))) Database::getInstance()->autoTranslateAll();
   