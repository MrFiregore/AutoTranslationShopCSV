<?php
    
    
    namespace firegore\AutoTranslationShopCSV;
    
    
    trait Singleton
    {
        private static $instance;
        
        private final function __construct ()
        {
        }
        
        public final static function getInstance ()
        {
            if (!self::$instance) {
                self::$instance = new self;
            }
            
            return self::$instance;
        }
        
        private final function __clone ()
        {
        }
        
        private final function __wakeup ()
        {
        }
    }