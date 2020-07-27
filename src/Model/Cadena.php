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
     * Almacena todas las cadenas en cualquier idoioma
     *
     * @ORM\Entity
     * @ORM\Table(name="firegore_cadena",
     *     uniqueConstraints={
     *        @ORM\UniqueConstraint(name="firegore_cadena_unique",
     *            columns={"nombre", "id_idioma"})
     *    }
     *     )
     */
    class Cadena extends Base
    {
        
        
        /**
         * @ORM\Column(type="text",length=5000, nullable=false)
         * @var string
         */
        protected $nombre;
        /**
         *
         * @ORM\ManyToOne(targetEntity="Idioma")
         * @ORM\JoinColumn(name="id_idioma", referencedColumnName="id")
         */
        protected $idioma;
        
        /**
         * @return mixed
         */
        public function getNombre ()
        {
            return $this->nombre;
        }
        
        /**
         * @param   mixed   $nombre
         *
         * @return Cadena
         */
        public function setNombre ($nombre)
        {
            $this->nombre = $nombre;
            return $this;
        }
        
        /**
         * @return Idioma
         */
        public function getIdioma ()
        {
            return $this->idioma;
        }
        
        /**
         * @param   mixed   $idioma
         *
         * @return Cadena
         */
        public function setIdioma ($idioma)
        {
            $this->idioma = $idioma;
            return $this;
        }
        
        public function __toString ()
        {
            // TODO: Implement __toString() method.
            return $this->getNombre();
        }
        
    }
