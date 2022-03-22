<?php

declare(strict_types=1);

namespace Doctrine\Tests\ORM\Functional\Ticket;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\Tests\OrmFunctionalTestCase;

/**
 * @group GH-9600
 */
class GH9600Test extends OrmFunctionalTestCase
{
    public function testMySetup(): void
    {
        $article = new Article();
        $article2 = new Article();

        $this->_em->persist($article);
        $this->_em->persist($article2);

        $articleAttribute = new ArticleAttribute('foo', 'value', $article);
        $articleAttribute2 = new ArticleAttribute('foo', 'value', $article2);

        $this->_em->persist($articleAttribute);
        $this->_em->persist($articleAttribute2);

#        $this->_em->flush();

        var_dump($this->_em->getUnitOfWork()->getIdentityMap());


        self::assertTrue($this->_em->getUnitOfWork()->isInIdentityMap($articleAttribute));
        self::assertTrue($this->_em->getUnitOfWork()->isInIdentityMap($articleAttribute2));
        self::assertTrue(in_array($articleAttribute, $this->_em->getUnitOfWork()->getIdentityMap()[ArticleAttribute::class], true)); //true
        self::assertTrue(in_array($articleAttribute2, $this->_em->getUnitOfWork()->getIdentityMap()[ArticleAttribute::class], true)); //false - expects true
    }
}

/**
 * @Entity
 */
class ArticleAttribute
{
    /**
     * @Id
     * @ManyToOne(targetEntity="Article")
     */
    private $article;

    /**
     * @Id
     * @Column(type="string")
     */
    private $attribute;

    /**
     * @Column(type="string")
     */
    private $value;

    public function __construct($name, $value, $article)
    {
        $this->attribute = $name;
        $this->value = $value;
        $this->article = $article;
    }
}

/**
 * @Entity
 */
class Article
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    private $id;

    /**
     * @Column(type="string")
     */
    private $title;
}
