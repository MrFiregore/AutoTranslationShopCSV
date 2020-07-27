<?php
    
    
    namespace firegore\AutoTranslationShopCSV;
    
    
    use firegore\AutoTranslationShopCSV\Model\Categoria;
    use firegore\AutoTranslationShopCSV\Model\Idioma;
    use firegore\AutoTranslationShopCSV\Model\Producto;
    use firegore\AutoTranslationShopCSV\Translator\Codes;
    use League\Csv\CharsetConverter;
    use League\Csv\Reader;
    
    class CSVReader
    {
        /**
         * Path to csv file.
         *
         * @var string $path
         */
        protected $path = "";
        
        /**
         * Actual category.
         *
         * @var Categoria $category
         */
        protected $category = "";
        
        
        /**
         * CSVReader constructor.
         *
         * @param   string   $path
         */
        public function __construct ($path)
        {
            $this->setPath(realpath($path))->readCSV();
        }
        
        public function readCSV ()
        {
            $csv = Reader::createFromPath($this->getPath(), 'r');
            CharsetConverter::addTo($csv, 'iso-8859-1', 'UTF-8');
            $csv->setDelimiter(';');
            $csv->setHeaderOffset(0);
            $records = $csv->getRecords();
            foreach ($records as $record) $this->readRecord($record);
        }
        
        
        public function readRecord ($record)
        {
            $isCategory = !!!(count(
                    array_filter(
                        $record,
                        function ($value) {
                            return !is_null($value) && $value !== '';
                        }
                    )
                ) - 1);
            $isCategory ? $this->storeCategory($record["nombre"]) : $this->storeProduct($record);
            
        }
        
        /**
         * @param   string   $name
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Categoria
         */
        public function storeCategory ($name)
        {
            $cadena = Database::getInstance()->getCadena($name);
            //Check if category is stored
            if (!($category = Database::getInstance()->getEntityManager()->getRepository(":Categoria")->findOneBy(["nombre" => $cadena]))) {
                $category = (new Categoria())->setNombre($cadena);
                Database::getInstance()->getEntityManager()->persist($category);
                Database::getInstance()->getEntityManager()->flush();
            }
            $this->setCategory($category);
        }
        
        /**
         * @param   array   $product
         */
        public function storeProduct ($product)
        {
            $nombre_cadena      = Database::getInstance()->getCadena($product["nombre"]);
            $decripcion_cadena  = Database::getInstance()->getCadena($product["descripción"]);
            $precio             = floatval($product["precio"]);
            $stock              = intval($product["stock"]);
            $fecha_ultima_venta = new \DateTime($product["fecha_ultima_venta"]);
            
            //Check if category is stored
            if (
            !($producto = Database::getInstance()->getEntityManager()->getRepository(":Producto")->findOneBy(["nombre" => $nombre_cadena, "categoria" => $this->getCategory()]))
            ) {
                $producto =
                    (new Producto())
                        ->setNombre($nombre_cadena)
                        ->setCategoria($this->getCategory())
                        ->setDescripcion($decripcion_cadena)
                        ->setFechaUltimaVenta($fecha_ultima_venta)
                        ->setPrecio($precio)
                        ->setStock($stock);
                Database::getInstance()->getEntityManager()->persist($producto);
                Database::getInstance()->getEntityManager()->flush();
            }
        }
        
        /**
         * @return string
         */
        public function getPath ()
        {
            return $this->path;
        }
        
        /**
         * @param   string   $path
         *
         * @return CSVReader
         */
        public function setPath (string $path)
        {
            $this->path = $path;
            return $this;
        }
        
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Categoria
         */
        public function getCategory ()
        {
            return $this->category;
        }
        
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Categoria   $category
         *
         * @return CSVReader
         */
        public function setCategory (\firegore\AutoTranslationShopCSV\Model\Categoria $category)
        {
            $this->category = $category;
            return $this;
        }
        
        
    }