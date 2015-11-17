<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
$loader->add('FOS',__DIR__.'/../vendor');
$loader->add('Ladybug',__DIR__.'/../vendor/RaulFraile/Bundle');
$loader->add('RaulFraile\Bundle',__DIR__.'/../vendor');

return $loader;
