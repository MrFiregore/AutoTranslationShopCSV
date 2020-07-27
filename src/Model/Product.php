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
     * @ORM\Entity(repositoryClass="firegore\AutoTranslationShopCSV\Repository\ProductRepository")
     * @ORM\Table(name="firegore_product")
     */
    class Product extends Base
    {
        /**
         * @ORM\ManyToOne(targetEntity="Category")
         * @ORM\JoinColumn(name="id_category", referencedColumnName="id", nullable=true, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Category $category
         */
        protected $category;
        /**
         * @ORM\ManyToOne(targetEntity="Word")
         * @ORM\JoinColumn(name="id_word", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Word $name
         */
        protected $name;
        /**
         * @ORM\ManyToOne(targetEntity="Word")
         * @ORM\JoinColumn(name="id_description", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         *
         * @var \firegore\AutoTranslationShopCSV\Model\Word $description
         */
        protected $description;
    
        /**
         * @ORM\Column(type="float", options={"default" : 0.0}, precision=2)
         * @var float $price
         */
        protected $price;
        /**
         * @ORM\Column(type="integer", options={"default" : 0})
         * @var int $stock
         */
        protected $stock;
    
        /**
         * @ORM\Column(type="datetime",nullable=true)
         * @var DateTime $last_sale_date
         */
        protected $last_sale_date;
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Category
         */
        public function getCategory ()
        {
            return $this->category;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Category   $category
         *
         * @return Product
         */
        public function setCategory (Category $category)
        {
            $this->category = $category;
            return $this;
        }
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         */
        public function getName ()
        {
            return $this->name;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Word   $name
         *
         * @return Product
         */
        public function setName (Word $name)
        {
            $this->name = $name;
            return $this;
        }
    
        /**
         * @return \firegore\AutoTranslationShopCSV\Model\Word
         */
        public function getDescription ()
        {
            return $this->description;
        }
    
        /**
         * @param   \firegore\AutoTranslationShopCSV\Model\Word   $description
         *
         * @return Product
         */
        public function setDescription (Word $description)
        {
            $this->description = $description;
            return $this;
        }
    
        /**
         * @return float
         */
        public function getPrice ()
        {
            return $this->price;
        }
    
        /**
         * @param   float   $price
         *
         * @return Product
         */
        public function setPrice (float $price)
        {
            $this->price = $price;
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
         * @return Product
         */
        public function setStock (int $stock)
        {
            $this->stock = $stock;
            return $this;
        }
    
        /**
         * @return \DateTime
         */
        public function getLastSaleDate ()
        {
            return $this->last_sale_date;
        }
    
        /**
         * @param   \DateTime   $last_sale_date
         *
         * @return Product
         */
        public function setLastSaleDate (DateTime $last_sale_date)
        {
            $this->last_sale_date = $last_sale_date;
            return $this;
        }
        
    }
