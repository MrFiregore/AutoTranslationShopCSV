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
     *
     * @ORM\Entity
     * @ORM\Table(name="firegore_translation")
     */
    class Translation extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Word")
         * @ORM\JoinColumn(name="id_word", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Word $word
         */
        protected $word;

        /**
         * @ORM\ManyToOne(targetEntity="Word")
         * @ORM\JoinColumn(name="id_translated_word", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Word $translated_word
         */
        protected $translated_word;

     

        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         */
        public function getWord ()
        {
            return $this->word;
        }

        /**
         * @param \firegore\AutoTranslationShopCSV\Model\Word   $word
         *
         * @return Translation
         */
        public function setWord ($word)
        {
            $this->word = $word;
            return $this;
        }

        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         */
        public function getTranslatedWord ()
        {
            return $this->translated_word;
        }

        /**
         * @param \firegore\AutoTranslationShopCSV\Model\Word   $translated_word
         *
         * @return Translation
         */
        public function setTranslatedWord ($translated_word)
        {
            $this->translated_word = $translated_word;
            return $this;
        }

    }
