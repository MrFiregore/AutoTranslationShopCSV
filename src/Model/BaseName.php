<?php

    namespace firegore\AutoTranslationShopCSV\Model;

    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\ORM\Mapping as ORM;

    /**
     *
     * @ORM\MappedSuperclass
     * @ORM\HasLifecycleCallbacks
     */
    class BaseName extends Base
    {
        /**
         * @ORM\Column(type="string", length=255, nullable=true, options={"default":NULL})
         * @var string
         */
        protected $name = null;

        /**
         * @return string
         */
        public function getName ()
        {
            return $this->name;
        }

        /**
         * @param string $name
         *
         * @return $this
         */
        public function setName ($name)
        {
            $this->name = $name;
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
