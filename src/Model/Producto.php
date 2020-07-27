<?php
    /**
     * Created by PhpStorm.
     * User: Firegore
     * Date: 19/10/2018
     * Time: 19:55
     */

    namespace firegore\AutoTranslationShopCSV\Model;

    use DateTime;
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\Entity(repositoryClass="firegore\AutoTranslationShopCSV\Repository\ProductoRepository")
     * @ORM\Table(name="firegore_producto")
     */
    class Producto extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Categoria")
         * @ORM\JoinColumn(name="id_categoria", referencedColumnName="id", nullable=true, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Categoria $categoria
         */
        protected $categoria;
        /**
         * @ORM\ManyToOne(targetEntity="Cadena")
         * @ORM\JoinColumn(name="id_nombre_cadena", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Cadena $nombre
         */
        protected $nombre;
        /**
         * @ORM\ManyToOne(targetEntity="Cadena")
         * @ORM\JoinColumn(name="id_descripcion_cadena", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Cadena $descripcion
         */
        protected $descripcion;
    
        /**
         * @ORM\Column(type="float", options={"default" : 0.0}, precision=2)
         * @var float $precio
         */
        protected $precio;
        /**
         * @ORM\Column(type="integer", options={"default" : 0})
         * @var int $stock
         */
        protected $stock;
    
        /**
         * @ORM\Column(type="datetime",nullable=true)
         * @var DateTime $fecha_ultima_venta
         */
        protected $fecha_ultima_venta;
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Categoria
         */
        public function getCategoria ()
        {
            return $this->categoria;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Categoria   $categoria
         *
         * @return Producto
         */
        public function setCategoria (Categoria $categoria)
        {
            $this->categoria = $categoria;
            return $this;
        }
    
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
         * @return Producto
         */
        public function setNombre (Cadena $nombre)
        {
            $this->nombre = $nombre;
            return $this;
        }
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Cadena
         */
        public function getDescripcion ()
        {
            return $this->descripcion;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Cadena   $descripcion
         *
         * @return Producto
         */
        public function setDescripcion (Cadena $descripcion)
        {
            $this->descripcion = $descripcion;
            return $this;
        }
    
        /**
         * @return float
         */
        public function getPrecio ()
        {
            return $this->precio;
        }
    
        /**
         * @param   float   $precio
         *
         * @return Producto
         */
        public function setPrecio (float $precio)
        {
            $this->precio = $precio;
            return $this;
        }
    
        /**
         * @return int
         */
        public function getStock ()
        {
            return $this->stock;
        }
    
        /**
         * @param   int   $stock
         *
         * @return Producto
         */
        public function setStock (int $stock)
        {
            $this->stock = $stock;
            return $this;
        }
    
        /**
         * @return \DateTime
         */
        public function getFechaUltimaVenta ()
        {
            return $this->fecha_ultima_venta;
        }
    
        /**
         * @param   \DateTime   $fecha_ultima_venta
         *
         * @return Producto
         */
        public function setFechaUltimaVenta (DateTime $fecha_ultima_venta)
        {
            $this->fecha_ultima_venta = $fecha_ultima_venta;
            return $this;
        }
        
    }
