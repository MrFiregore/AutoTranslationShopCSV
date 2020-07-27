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
     * @ORM\Table(name="firegore_idioma")
     */
    class Idioma extends BaseNombre
    {
        /**
         * @ORM\Column(type="string", length=64, nullable=true, options={"default":NULL}, unique=true)
         * @var string
         */
        protected $nombre_corto = null;

        /**
         * @return string
         */
        public function getNombreCorto ()
        {
            return $this->nombre_corto;
        }

        /**
         * @param string $nombre_corto
         *
         * @return Idioma
         */
        public function setNombreCorto($nombre_corto)
        {
            $this->nombre_corto = $nombre_corto;
            return $this;
        }

    }
