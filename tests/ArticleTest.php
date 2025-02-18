<?php
namespace App\Tests;
use PHPUnit\Framework\TestCase;
use App\Entity\Article;

class ArticleTest extends TestCase
{
    public function testArticleCreation()
    {
        $article = new Article();
        $article->setTitre("Un super article");
        $article->setContenu("Contenu de l'article");

        $this->assertEquals("Un super article", $article->getTitre());
        $this->assertEquals("Contenu de l'article", $article->getContenu());
    }
}
