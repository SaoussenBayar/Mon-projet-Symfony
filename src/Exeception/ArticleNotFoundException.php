<?php
namespace App\Exception;

class ArticleNotFoundException extends \Exception
{
    protected $message = 'Article non trouvé!'; 
}