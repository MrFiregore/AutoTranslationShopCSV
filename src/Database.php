<?php
    
    
    namespace firegore\AutoTranslationShopCSV;
    
    
    use Doctrine\ORM\EntityManager;
    use firegore\AutoTranslationShopCSV\Model\Word;
    use firegore\AutoTranslationShopCSV\Model\Language;
    use firegore\AutoTranslationShopCSV\Model\Translation;
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
         * @return \firegore\AutoTranslationShopCSV\Model\Language
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function getLanguage ($code= false,$name = "")
        {
            if (!$code) $code = constant(Codes::class."::".strtoupper(locale_get_default()));
            $lang = $this->getEntityManager()->getRepository(":Language")->findOneBy(["short_name" => $code]);
            if (!$lang) {
                $lang = (new Language())->setName($name)->setShortName($code);
                $this->getEntityManager()->persist($lang);
                $this->getEntityManager()->flush();
            }
            return $lang;
        }
    
        /**
         * @param   string                                                 $string
         * @param   \firegore\AutoTranslationShopCSV\Model\Language|null   $language
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function getWord(string $string, Language $language = null){
            if (!$language) $language = $this->getLanguage();
            $word = $this->getEntityManager()->getRepository(":Word")->findOneBy(["name" => $string, "language"=>$language]);
            if (!$word) {
                $word = (new Word())->setName($string)->setLanguage($language);
                $this->getEntityManager()->persist($word);
                $this->getEntityManager()->flush();
            }
            return $word;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Word   $target
         * @param   \firegore\AutoTranslationShopCSV\Model\Word   $trans
         *
         * @return \firegore\AutoTranslationShopCSV\Model\Translation
         * @throws \Doctrine\ORM\ORMException
         * @throws \Doctrine\ORM\OptimisticLockException
         */
        public function setTranslation(Word $target, Word $trans){
            $translation = $this->getEntityManager()->getRepository(":Translation")->findOneBy(["word" => $target, "translated_word"=>$trans]);
            if (!$translation) {
                $translation = (new Translation())->setWord($target)->setTranslatedword($trans);
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
                                    FROM  firegore_word ic
                                    LEFT JOIN firegore_translation it on ic.id = it.id_word
                                    LEFT JOIN firegore_language ii on ic.id_language = ii.id
                                    WHERE ii.name LIKE '%Spanish%'
                                    GROUP BY  ic.id
                                    HAVING count(it.id) < (SELECT count(*) FROM firegore_language)-1");
            $result = $query->fetchAll();
            $collection = collect($result);
    
            $collection->transform(function ($item, $key) {
                return (int)$item["id"];
            });
            foreach ($entity_manager->getRepository(":Word")->findBy(["id"=>$collection->toArray()]) as $word) {
                /**
                 * @var Word $word
                 */
                $query          =
                    $entity_manager->getConnection()
                                   ->query(
                                       "SELECT ii.*
                                        FROM firegore_language ii
                                        WHERE ii.id NOT IN (
                                            SELECT ic.id_language
                                            FROM firegore_word ic
                                              WHERE ic.id = {$word->getId()}
                                            UNION
                                              SELECT ic.id_language
                                              FROM firegore_translation it
                                              JOIN firegore_word ic on it.id_word = ic.id AND ic.id = {$word->getId()}
                                            )");
                $result = $query->fetchAll();
                $collection = collect($result);
        
                $collection->transform(function ($item, $key) {
                    return (int)$item["id"];
                });
                foreach ($entity_manager->getRepository(":Language")
                                        ->findBy(["id" => $collection->toArray()]) as $language) {
                    /**
                     * @var Language $language
                     */
                    if (($translation_response = $translate->translate(
                            $word->getName(),
                            $language->getShortName(),
                            $word->getLanguage()->getShortName()
                        )->getTranslation()) && $translation_response === "") continue;
                    
                    $translated_word = $this->getWord($translation_response, $language);
                    
                    $this->setTranslation($word, $translated_word);
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