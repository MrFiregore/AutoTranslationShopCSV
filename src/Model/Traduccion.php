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
     * @ORM\Table(name="firegore_traduccion")
     */
    class Traduccion extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Cadena")
         * @ORM\JoinColumn(name="id_cadena", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Cadena $cadena
         */
        protected $cadena;

        /**
         * @ORM\ManyToOne(targetEntity="Cadena")
         * @ORM\JoinColumn(name="id_cadena_traducida", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Cadena $cadena_traducida
         */
        protected $cadena_traducida;

     

        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Cadena
         */
        public function getCadena ()
        {
            return $this->cadena;
        }

        /**
         * @param \firegore\AutoTranslationShopCSV\Model\Cadena $cadena
         *
         * @return Traduccion
         */
        public function setCadena ($cadena)
        {
            $this->cadena = $cadena;
            return $this;
        }

        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Cadena
         */
        public function getCadenaTraducida ()
        {
            return $this->cadena_traducida;
        }

        /**
         * @param \firegore\AutoTranslationShopCSV\Model\Cadena $cadena_traducida
         *
         * @return Traduccion
         */
        public function setCadenaTraducida ($cadena_traducida)
        {
            $this->cadena_traducida = $cadena_traducida;
            return $this;
        }

    }
