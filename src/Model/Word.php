<?php
    /**
     * Created by PhpStorm.
     * User: Firegore
     * Date: 19/10/2018
     * Time: 19:55
     */
    
    namespace firegore\AutoTranslationShopCSV\Model;
    
    use Doctrine\ORM\Mapping as ORM;
    
    /**
     * Store all words of any language
     *
     * @ORM\Entity
     * @ORM\Table(name="firegore_word",
     *     uniqueConstraints={
     *        @ORM\UniqueConstraint(name="firegore_word_unique",
     *            columns={"nombre", "id_language"})
     *    }
     *     )
     */
    class Word extends Base
    {
        
        
        /**
         * @ORM\Column(type="text",length=5000, nullable=false)
         * @var string
         */
        protected $name;
        /**
         *
         * @ORM\ManyToOne(targetEntity="Language")
         * @ORM\JoinColumn(name="id_language", referencedColumnName="id")
         */
        protected $language;
        
        /**
         * @return mixed
         */
        public function getName ()
        {
            return $this->name;
        }
        
        /**
         * @param   mixed   $name
         *
         * @return Word
         */
        public function setName ($name)
        {
            $this->name = $name;
            return $this;
        }
        
        /**
         * @return Language
         */
        public function getLanguage ()
        {
            return $this->language;
        }
        
        /**
         * @param   mixed   $language
         *
         * @return Word
         */
        public function setLanguage ($language)
        {
            $this->language = $language;
            return $this;
        }
        
        public function __toString ()
        {
            return $this->getName();
        }
        
    }
