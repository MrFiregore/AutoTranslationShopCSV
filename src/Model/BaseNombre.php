<?php

    namespace firegore\AutoTranslationShopCSV\Model;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\MappedSuperclass
     * @ORM\HasLifecycleCallbacks
     */
    class BaseNombre extends Base
    {
        /**
         * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
         * @var string
         */
        protected $nombre = null;

        /**
         * @return string
         */
        public function getNombre ()
        {
            return $this->nombre;
        }

        /**
         * @param string $nombre
         *
         * @return $this
         */
        public function setNombre ($nombre)
        {
            $this->nombre = $nombre;
            return $this;
        }


        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PrePersist
         */
        public function prePersist ($args)
        {
            parent::prePersist($args);
        }

        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PreUpdate
         */
        public function preUpdate ($args)
        {
            parent::preUpdate($args);
        }

    }
