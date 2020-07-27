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
     * @ORM\Table(name="firegore_category")
     */
    class Category extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Word")
         * @ORM\JoinColumn(name="id_word", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Word $word
         */
        protected $word;
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         */
        public function getWord ()
        {
            return $this->word;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Word   $word
         *
         * @return Category
         */
        public function setWord (\firegore\AutoTranslationShopCSV\Model\Word $word)
        {
            $this->word = $word;
            return $this;
        }


      

    }
