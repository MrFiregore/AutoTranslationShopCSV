<?php
    /**
     * Translation
     *
     * @copyright Copyright © 2020 <company_name>. All rights reserved.
     * @author    <user_email>
     */
    
    namespace firegore\AutoTranslationShopCSV\Translator;
    
    
    use Exception;

    class Translation
    {
        protected $translation;
        protected $translations;
        protected $definitions;
        protected $examples;
        protected $collations;
        protected $word_transcription;
        protected $translation_transcription;
        protected $synonyms;
        
        public function __construct (array $data = [])
        {
            if (empty($data))
                throw new Exception("No hay datos de la api de Google");
            $this->decodeData($data);
        }
        
        /**
         * @return mixed
         */
        public function getDefinitions ()
        {
            return $this->definitions;
        }
        
        /**
         * @param   mixed   $definitions
         *
         * @return Translation
         */
        public function setDefinitions ($definitions)
        {
            $this->definitions = $definitions;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getExamples ()
        {
            return $this->examples;
        }
        
        /**
         * @param   mixed   $examples
         *
         * @return Translation
         */
        public function setExamples ($examples)
        {
            $this->examples = $examples;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getCollations ()
        {
            return $this->collations;
        }
        
        /**
         * @param   mixed   $collations
         *
         * @return Translation
         */
        public function setCollations ($collations)
        {
            $this->collations = $collations;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getTranslations ()
        {
            return $this->translations;
        }
        
        /**
         * @param   mixed   $translations
         *
         * @return Translation
         */
        public function setTranslations ($translations)
        {
            $this->translations = $translations;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getSynonyms ()
        {
            return $this->synonyms;
        }
        
        /**
         * @param   mixed   $synonyms
         *
         * @return Translation
         */
        public function setSynonyms ($synonyms)
        {
            $this->synonyms = $synonyms;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getTranslation ()
        {
            return $this->translation;
        }
        
        /**
         * @param   mixed   $translation
         *
         * @return Translation
         */
        public function setTranslation ($translation)
        {
            $this->translation = $translation;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getWordTranscription ()
        {
            return $this->word_transcription;
        }
        
        /**
         * @param   mixed   $word_transcription
         *
         * @return Translation
         */
        public function setWordTranscription ($word_transcription)
        {
            $this->word_transcription = $word_transcription;
            return $this;
        }
        
        /**
         * @return mixed
         */
        public function getTranslationTranscription ()
        {
            return $this->translation_transcription;
        }
        
        /**
         * @param   mixed   $translation_transcription
         *
         * @return Translation
         */
        public function setTranslationTranscription ($translation_transcription)
        {
            $this->translation_transcription = $translation_transcription;
            return $this;
        }
        
        protected function decodeData ($data)
        {
            $this->setTranslation($data[0][0][0])
                 ->setWordTranscription(isset($data[0][1]) ? $data[0][1][3] : $data[0][0][0])
                 ->setTranslationTranscription(isset($data[0][1]) ? $data[0][1][2] : $data[0][0][0])
                ->setDetailedSynonyms($data)
                ->setDetailedDefinitions($data)
                ->setDetailedTranslations($data)
                ->setDetailedTranslation($data)
                ->setDetailedCollation($data)
                ->setDetailedExamples($data)
            ;
            
        }
        
        protected function setDetailedSynonyms ($data)
        {
            if (!isset($data[11])) return $this;
            $synonymsObj = [];
            foreach ($data[11] as $datum) {
                $synonyms = [];
                foreach ($datum[1] as $item) {
                    $synonyms[] = $item[0];
                }
                $synonymsObj[$datum[0]] = $synonyms;
            }
            
            return $this->setSynonyms($synonymsObj);
        }
        
        protected function setDetailedTranslation ($data)
        {
            if (!isset($data[11])) return $this;
            $synonymsObj = [];
            foreach ($data[11] as $datum) {
                $synonyms = [];
                foreach ($datum[1] as $item) {
                    $synonyms[] = $item[0];
                }
                $synonymsObj[$datum[0]] = $synonyms;
            }
            
            return $this->setSynonyms($synonymsObj);
        }
        
        protected function setDetailedDefinitions ($data)
        {
            if (!isset($data[12])) return $this;
            $defObj = [];
            foreach ($data[12] as $datum) {
                $def = [];
                foreach ($datum[1] as $item) {
                    $def[] = [
                        "definition" => isset($item[0]) ? $item[0] : "",
                        "example"    => isset($item[2]) ? $item[2] : "",
                    ];
                }
                $defObj[$datum[0]] = $def;
            
            }
            return $this->setDefinitions($defObj);
        }
        protected function setDetailedExamples ($data)
        {
            if (!isset($data[13])) return $this;
            $exObj = [];
            foreach ($data[13][0] as $datum) {
                $exObj[] = $datum[0];
            
            }
            return $this->setExamples($exObj);
        }
        protected function setDetailedTranslations ($data)
        {
            if (!isset($data[1])) return $this;
            $transObj = [];
            foreach ($data[1] as $translation) {
                $wordType = $translation[0];
                foreach ($translation[2] as $item) {
                    $transObj[$wordType][]=[
                        "translation"=>$item[0],
                        "synonyms"=>$item[1],
                        "frequency"=>$item[3],
                    ];
                }
            }
            return $this->setTranslations($transObj);
        }
        protected function setDetailedCollation ($data)
        {
            if (!isset($data[14])) return $this;
            return $this->setDefinitions($data[14][0]);
        }
    }