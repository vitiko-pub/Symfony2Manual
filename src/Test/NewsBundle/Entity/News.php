<?php

namespace Test\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Test\NewsBundle\Entity\News
 *
 * @ORM\Table(name="news")
 * @ORM\Entity
 */
class News
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var text $announce
     *
     * @ORM\Column(name="announce", type="text", nullable=true)
     */
    private $announce;

    /**
     * @var text $text
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var datetime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var datetime $updatedAt
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var date $pubDate
     *
     * @ORM\Column(name="pub_date", type="date", nullable=true)
     */
    private $pubDate;

    /**
     * @var $newsCategory
     *
     * @ORM\ManyToOne(targetEntity="NewsCategory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="news_category_id", referencedColumnName="id")
     * })
     * })
     * @Assert\NotBlank
     */
    private $newsCategory;

    /**
     * @var $newsLinks
     *
     * @ORM\OneToMany(targetEntity="NewsLink", mappedBy="news", cascade={"all"}, orphanRemoval=true)
     * @ORM\OrderBy({"pos" = "ASC"})
     */
    protected $newsLinks;



    function __construct()
    {
       $this->newsLinks = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
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
     * Set announce
     *
     * @param text $announce
     */
    public function setAnnounce($announce)
    {
        $this->announce = $announce;
    }

    /**
     * Get announce
     *
     * @return text
     */
    public function getAnnounce()
    {
        return $this->announce;
    }

    /**
     * Set text
     *
     * @param text $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return text
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set createdAt
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get updatedAt
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set pubDate
     *
     * @param date $pubDate
     */
    public function setPubDate($pubDate)
    {
        $this->pubDate = $pubDate;
    }

    /**
     * Get pubDate
     *
     * @return date
     */
    public function getPubDate()
    {
        return $this->pubDate;
    }



    public function getPubYear()
    {
        return $this->pubDate->format ('Y');
    }

    /**
     * Set newsCategory
     *
     * @param Test\NewsBundle\Entity\NewsCategory $newsCategory
     */
    public function setNewsCategory(\Test\NewsBundle\Entity\NewsCategory $newsCategory)
    {
        $this->newsCategory = $newsCategory;
    }

    /**
     * Get newsCategory
     *
     * @return Test\NewsBundle\Entity\NewsCategory
     */
    public function getNewsCategory()
    {
        return $this->newsCategory;
    }

    /**
     * Add newsLinks
     *
     * @param Test\NewsBundle\Entity\NewsLink $newsLinks
     */
    public function addNewsLinks(\Test\NewsBundle\Entity\NewsLink $newsLinks)
    {
        $this->newsLinks[] = $newsLinks;
    }

    /**
     * Get newsLinks
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getNewsLinks()
    {
        return $this->newsLinks;
    }


    public function setNewsLinks( $newsLinks)
    {
        $this->newsLinks= $newsLinks;

        //foreach ( $this->newsLinks  as $link) $link->setPos (555);

        foreach ($this->newsLinks as $pos => $link)
        {
           // print '<br>'.$link.' '.$pos;
            $link->setNews ($this); //->setPos ($pos);
        }
      /// die ('xxxxxxx');
    }


    function __toString()
    {
        return $this->getTitle() ? $this->getTitle() : '[Без названия]';
    }
}