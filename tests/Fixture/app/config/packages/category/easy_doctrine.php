<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use EonX\EasyDoctrine\Tests\Fixture\App\Entity\Category;
use Symfony\Config\EasyDoctrineConfig;

return static function (EasyDoctrineConfig $easyDoctrineConfig): void {
    $easyDoctrineConfig
        ->deferredDispatcherEntities([
            Category::class,
        ]);
};
