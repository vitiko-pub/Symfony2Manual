<?php
namespace Test\NewsBundle\Entity\Search;

use Symfony\Component\Validator\Constraints as Assert;

class News
{
    /**
     * Строка поиска
     * @var string
     */
    public $search;

     /**
     * Идентификатор категории новостей
     * @var integer
     */
    public $category;

    /**
     * Дата с которой искать новости
     * @var DateTime
     * @Assert\DateTime
     */
    public $dateFrom;

    /**
     * Дата, до которой искать новости
     * @var DateTime
     * @Assert\DateTime
     */
    public $dateTo;

    public $year;
}
