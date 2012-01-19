<?php

namespace Test\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Test\NewsBundle\Entity\NewsLink
 *
 * @ORM\Table(name="news_link")
 * @ORM\Entity
 */
class NewsLink
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
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=false)
     */
    private $url;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="string", length=255, nullable=false)
     */
    private $text;


    /**
     * @var string $pos
     *
     * @ORM\Column(name="pos", type="integer", nullable=true)
     */
    private $pos;

    /**
     * @var $news
     *
     * @ORM\ManyToOne(targetEntity="News", inversedBy="newsLinks")
     * @ORM\JoinColumn(name="news_id", referencedColumnName="id")
     *
     */
    private $news;


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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set news
     *
     * @param Test\NewsBundle\Entity\News $news
     */
    public function setNews(\Test\NewsBundle\Entity\News $news)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * Get news
     *
     * @return Test\NewsBundle\Entity\News
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Set text
     *
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function __toString()
    {
        return $this->getUrl();
    }

    /**
     * @param string $pos
     * @return ${CLASS_NAME}
     */
    public function setPos($pos)
    {
        $this->pos = $pos;
        return $this;
    }

    /**
     * @return string
     */
    public function getPos()
    {
        return $this->pos;
    }


}