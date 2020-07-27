<?php
    
    
    namespace firegore\AutoTranslationShopCSV;
    
    
    use Doctrine\ORM\EntityManager;
    use firegore\AutoTranslationShopCSV\Model\Cadena;
    use firegore\AutoTranslationShopCSV\Model\Idioma;
    use firegore\AutoTranslationShopCSV\Model\Traduccion;
    use firegore\AutoTranslationShopCSV\Translator\Codes;
    use firegore\AutoTranslationShopCSV\Translator\GoogleTranslator;

    class Database
    {
        use Singleton;
        
        /**
         * @var \Doctrine\ORM\EntityManager $entity_manager
         */
        protected $entity_manager;
    
        /**
         * returns the language with the given code, otherwise, create and returns the language
         *
         * @param   string   $code
         * @param   string   $name
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Idioma
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function getLanguage ($code= false,$name = "")
        {
            if (!$code) $code = constant(Codes::class."::".strtoupper(locale_get_default()));
            $lang = $this->getEntityManager()->getRepository(":Idioma")->findOneBy(["nombre_corto" => $code]);
            if (!$lang) {
                $lang = (new Idioma())->setNombre($name)->setNombreCorto($code);
                $this->getEntityManager()->persist($lang);
                $this->getEntityManager()->flush();
            }
            return $lang;
        }
    
        /**
         * @param   string                               $string
         * @param   \firegore\AutoTranslationShopCSV\Model\Idioma|null   $idioma
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Cadena
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function getCadena(string $string,Idioma $idioma = null){
            if (!$idioma) $idioma = $this->getLanguage();
            $cadena = $this->getEntityManager()->getRepository(":Cadena")->findOneBy(["nombre" => $string, "idioma"=>$idioma]);
            if (!$cadena) {
                $cadena = (new Cadena())->setNombre($string)->setIdioma($idioma);
                $this->getEntityManager()->persist($cadena);
                $this->getEntityManager()->flush();
            }
            return $cadena;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Cadena   $target
         * @param   \firegore\AutoTranslationShopCSV\Model\Cadena   $trans
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Traduccion
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function setTraduccion(Cadena $target,Cadena $trans){
            $translation = $this->getEntityManager()->getRepository(":Traduccion")->findOneBy(["cadena" => $target, "cadena_traducida"=>$trans]);
            if (!$translation) {
                $translation = (new Traduccion())->setCadena($target)->setCadenaTraducida($trans);
                $this->getEntityManager()->persist($translation);
                $this->getEntityManager()->flush();
            }
            return $translation;
        }
        
        
        public function autoTranslateAll(){
            $entity_manager = $this->getEntityManager();
            /**
             * @var GoogleTranslator $translate
             */
            $translate = GoogleTranslator::getInstance();
            $query          =
                $entity_manager->getConnection()
                               ->query(
                                   "SELECT ic.id
                                    FROM  firegore_cadena ic
                                    LEFT JOIN firegore_traduccion it on ic.id = it.id_cadena
                                    LEFT JOIN firegore_idioma ii on ic.id_idioma = ii.id
                                    WHERE ii.nombre LIKE '%Spanish%'
                                    GROUP BY  ic.id
                                    HAVING count(it.id) < (SELECT count(*) FROM firegore_idioma)-1");
            $result = $query->fetchAll();
            $collection = collect($result);
    
            $collection->transform(function ($item, $key) {
                return (int)$item["id"];
            });
            foreach ($entity_manager->getRepository(":Cadena")->findBy(["id"=>$collection->toArray()]) as $cadena) {
                /**
                 * @var Cadena $cadena
                 */
                $query          =
                    $entity_manager->getConnection()
                                   ->query(
                                       "SELECT ii.*
                                        FROM firegore_idioma ii
                                        WHERE ii.id NOT IN (
                                            SELECT ic.id_idioma
                                            FROM firegore_cadena ic
                                              WHERE ic.id = {$cadena->getId()}
                                            UNION
                                              SELECT ic.id_idioma
                                              FROM firegore_traduccion it
                                              JOIN firegore_cadena ic on it.id_cadena = ic.id AND ic.id = {$cadena->getId()}
                                            )");
                $result = $query->fetchAll();
                $collection = collect($result);
        
                $collection->transform(function ($item, $key) {
                    return (int)$item["id"];
                });
                foreach ($entity_manager->getRepository(":Idioma")
                                        ->findBy(["id" => $collection->toArray()]) as $idioma) {
                    /**
                     * @var Idioma $idioma
                     */
                    if (($respuesta_traduccion = $translate->translate(
                            $cadena->getNombre(),
                            $idioma->getNombreCorto(),
                            $cadena->getIdioma()->getNombreCorto()
                        )->getTranslation()) && $respuesta_traduccion === "") continue;
                    
                    $cadena_traducida = $this->getCadena($respuesta_traduccion, $idioma);
                    
                    $this->setTraduccion($cadena,$cadena_traducida);
                }
                $entity_manager->flush();
        
            }
        }
        
        
        
        
        
        
        
        /**
         * @return \Doctrine\ORM\EntityManager
         */
        public function getEntityManager ()
        {
            return $this->entity_manager;
        }
        
        /**
         * @param   \Doctrine\ORM\EntityManager   $entity_manager
         *
         * @return \firegore\AutoTranslationShopCSV\Database
         */
        public function setEntityManager (EntityManager $entity_manager)
        {
            $this->entity_manager = $entity_manager;
            return $this;
        }
    }