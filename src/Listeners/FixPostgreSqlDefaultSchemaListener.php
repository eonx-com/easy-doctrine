<?php
declare(strict_types=1);

namespace EonX\EasyDoctrine\Listeners;

use Doctrine\DBAL\Schema\PostgreSQLSchemaManager;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

/**
 * Workaround for an issue: https://github.com/doctrine/dbal/issues/1110.
 *
 * @author https://github.com/vudaltsov
 */
final class FixPostgreSqlDefaultSchemaListener
{
    /**
     * @throws \Doctrine\DBAL\Exception
     * @throws \Doctrine\DBAL\Schema\SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->createSchemaManager();

        if ($schemaManager instanceof PostgreSQLSchemaManager === false) {
            return;
        }

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if ($args->getSchema()->hasNamespace($namespace) === false) {
                $args->getSchema()
                    ->createNamespace($namespace);
            }
        }
    }
}
