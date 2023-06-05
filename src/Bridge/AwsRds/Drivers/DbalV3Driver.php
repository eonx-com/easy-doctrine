<?php

declare(strict_types=1);

namespace EonX\EasyDoctrine\Bridge\AwsRds\Drivers;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\API\ExceptionConverter;
use Doctrine\DBAL\Driver\Connection as DriverConnection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Schema\AbstractSchemaManager;

final class DbalV3Driver extends AbstractAwsRdsDriver implements Driver
{
    /**
     * @param mixed[] $params
     *
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function connect(array $params): DriverConnection
    {
        $params = $this->connectionParamsResolver->getParams($params);

        return $this->decorated->connect($params);
    }

    public function getDatabasePlatform(): AbstractPlatform
    {
        return $this->decorated->getDatabasePlatform();
    }

    public function getExceptionConverter(): ExceptionConverter
    {
        return $this->decorated->getExceptionConverter();
    }

    public function getSchemaManager(Connection $conn, AbstractPlatform $platform): AbstractSchemaManager
    {
        return $this->decorated->getSchemaManager($conn, $platform);
    }
}
