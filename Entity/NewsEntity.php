<?php

namespace FeedBundle\Entity;

use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(name="news")
 * @ORM\Entity(repositoryClass="FeedBundle\Repository\NewsEntityRepository")
 */
class NewsEntity extends AbstractEntity
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    protected $guid;

    /**
     * @Assert\NotBlank()
     *
     * @ORM\ManyToOne(targetEntity="CategoryEntity", inversedBy="name")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="text", nullable=false)
     * @Assert\NotBlank()
     */
    protected $description;

    /**
     *
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime")
     * @Assert\NotNull()
     */
    protected $pubDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    protected $link;

    /**
     * @ORM\OneToOne(targetEntity="MediaEntity")
     * @ORM\JoinColumn(name="media_id", onDelete="CASCADE", referencedColumnName="id")
     */
    protected $media;

    /**
     *
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * NewsEntity constructor.
     * @param string $guid
     * @param CategoryEntity $category
     * @param string $title
     * @param string $description
     * @param \DateTime $pubDate
     * @param string $link
     * @param MediaEntity|null $media
     */
    public function __construct(
        string $guid,
        CategoryEntity $category,
        string $title,
        string $description,
        \DateTime $pubDate,
        string $link,
        ?MediaEntity $media
    ) {
        $this->guid = $guid;
        $this->category = $category;
        $this->title = $title;
        $this->description = $description;
        $this->pubDate = $pubDate;
        $this->link = $link;
        $this->media = $media;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set guid
     *
     * @param string $guid
     *
     * @return $this
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;

        return $this;
    }

    /**
     * Get guid
     *
     * @return string
     */
    public function getGuid()
    {
        return $this->guid;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return $this
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set pubDate
     *
     * @param string $pubDate
     *
     * @return $this
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    /**
     * Get pubDate
     *
     * @return string
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return $this
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set media
     *
     * @param \FeedBundle\Entity\MediaEntity $media
     *
     * @return $this
     */
    public function setMedia(\FeedBundle\Entity\MediaEntity $media = null)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \FeedBundle\Entity\MediaEntity
     */
    public function getMedia()
    {
        return $this->media;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
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
