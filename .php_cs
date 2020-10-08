<?php

return PHPCsFixer\Config::create()
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in(__DIR__ . '/src') 
            ->in(__DIR__ . '/tests') 
    
    )->setUsingCache(true);
