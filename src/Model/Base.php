<?php

    namespace firegore\AutoTranslationShopCSV\Model;

    use DateTime;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\MappedSuperclass
     * @ORM\HasLifecycleCallbacks
     */
    class Base
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\Column(type="datetime")
         * @var DateTime
         */
        protected $fecha_creacion;

        /**
         * @ORM\Column(type="datetime")
         * @var DateTime
         */
        protected $fecha_actualizacion;

        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PrePersist
         */
        public function prePersist ($args)
        {
            $this->setCurrentDate($this->fecha_creacion);
            $this->setCurrentDate($this->fecha_actualizacion);
        }

        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PreUpdate
         */
        public function preUpdate ($args)
        {
            $this->setCurrentDate($this->fecha_actualizacion);
        }

        /**
         * @return mixed
         */
        public function getId ()
        {
            return $this->id;
        }

        public function getFechaCreacion ()
        {
            return $this->fecha_creacion;
        }

        protected function setFechaCreacion ($fecha_creacion)
        {
            $this->fecha_creacion = $fecha_creacion;
            return $this;
        }

        public function getFechaActualizacion ()
        {
            return $this->fecha_actualizacion;
        }

        protected function setFechaActualizacion ($fecha_actualizacion)
        {
            $this->fecha_actualizacion = $fecha_actualizacion;
            return $this;
        }


        /**
         * @return \DateTime
         */
        public function getCurrentDate()
        {
            return new DateTime("now");
        }

        public function setCurrentDate(&$prop)        {
            $prop = $this->getCurrentDate();
        }

        public function has($varName){
            return property_exists($this,$varName) && !empty($this->{$varName});
        }
    }
