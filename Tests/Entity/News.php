<?php

namespace VM5\EntityTranslationsBundle\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use VM5\EntityTranslationsBundle\Model\Translatable;
use VM5\EntityTranslationsBundle\Model\Translation;

/**
 * Class News
 * @package VM5\EntityTranslationsBundle\Tests
 * @ORM\Entity()
 */
class News implements Translatable
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var NewsTranslation[]|ArrayCollection
     * @ORM\OneToMany(targetEntity="NewsTranslation", mappedBy="translatable", cascade={"persist"}, orphanRemoval=true)
     */
    private $translations;

    /**
     * @var NewsTranslation
     */
    private $currentTranslation;

    /**
     * News constructor.
     * @param NewsTranslation[] $translations
     */
    public function __construct(array $translations)
    {
        $this->translations = new ArrayCollection($translations);
        foreach ($this->translations as $translation) {
            $translation->setTranslatable($this);
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function setCurrentTranslation(Translation $translation = null)
    {
        $this->currentTranslation = $translation;
    }

    /**
     * @return NewsTranslation
     */
    public function getCurrentTranslation()
    {
        return $this->currentTranslation;
    }

    public function addTranslation(NewsTranslation $translation)
    {
        $this->getTranslations()->add($translation);
        $translation->setTranslatable($this);
    }

    public function getTitle()
    {
        if ($this->currentTranslation !== null) {
            return $this->currentTranslation->getTitle();
        }

        return null;
    }
}