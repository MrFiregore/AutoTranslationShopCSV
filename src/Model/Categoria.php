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
     * @ORM\Table(name="firegore_categoria")
     */
    class Categoria extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Cadena")
         * @ORM\JoinColumn(name="id_cadena", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Cadena $cadena
         */
        protected $nombre;
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Cadena
         */
        public function getNombre ()
        {
            return $this->nombre;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Cadena   $nombre
         *
         * @return Categoria
         */
        public function setNombre (\firegore\AutoTranslationShopCSV\Model\Cadena $nombre)
        {
            $this->nombre = $nombre;
            return $this;
        }


      

    }
