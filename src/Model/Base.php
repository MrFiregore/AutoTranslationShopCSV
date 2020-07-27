<?php

    namespace firegore\AutoTranslationShopCSV\Model;

    use DateTime;
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
        protected $creation_date;

        /**
         * @ORM\Column(type="datetime")
         * @var DateTime
         */
        protected $update_date;

        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PrePersist
         */
        public function prePersist ($args)
        {
            $this->setCurrentDate($this->creation_date);
            $this->setCurrentDate($this->update_date);
        }

        /**
         * @var \Doctrine\ORM\Event\LifecycleEventArgs $args
         * @ORM\PreUpdate
         */
        public function preUpdate ($args)
        {
            $this->setCurrentDate($this->update_date);
        }

        /**
         * @return mixed
         */
        public function getId ()
        {
            return $this->id;
        }

        public function getCreationDate ()
        {
            return $this->creation_date;
        }

        protected function setCreationDate ($creation_date)
        {
            $this->creation_date = $creation_date;
            return $this;
        }

        public function getUpdateDate ()
        {
            return $this->update_date;
        }

        protected function setUpdateDate ($update_date)
        {
            $this->update_date = $update_date;
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
