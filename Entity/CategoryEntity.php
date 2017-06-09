<?php

namespace Gubarev\Bundle\FeedBundle\Entity;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Gubarev\Bundle\FeedBundle\Repository\CategoryEntityRepository")
 * @UniqueEntity(fields="name", message="Name is already taken.", repositoryMethod="findOneBy")
 */
class CategoryEntity extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=false, unique=true)
     * @ORM\OneToMany(targetEntity="NewsEntity", mappedBy="category")
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Gedmo\Slug(fields={"name"}, updatable=false, unique=true)
     */
    protected $slug;

    /**
     * CategoryEntity constructor.
     * @param string $name
     * @internal param $this
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray():array
    {
        $serializer = SerializerBuilder::create()->build();

        return $serializer->toArray($this);
    }
}
