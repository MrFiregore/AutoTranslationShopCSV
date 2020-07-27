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
     * @ORM\Entity
     * @ORM\Table(name="firegore_language")
     */
    class Language extends BaseName
    {
        /**
         * @ORM\Column(type="string", length=64, nullable=true, options={"default":NULL}, unique=true)
         * @var string $short_name
         */
        protected $short_name = null;

        /**
         * @return string
         */
        public function getShortName ()
        {
            return $this->short_name;
        }

        /**
         * @param string   $short_name
         *
         * @return Language
         */
        public function setShortName($short_name)
        {
            $this->short_name = $short_name;
            return $this;
        }

    }
