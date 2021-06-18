<?php
 
namespace Front\Containers;
 
use Plenty\Plugin\Templates\Twig;
 
class FrontContainer
{
    public function call(Twig $twig):string
    {
        return $twig->render('Front::content.Front');
    }
}