<?php

namespace Somecode\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Somecode\Framework\Console\CommandInterface;

class MigrateCommand implements CommandInterface
{
    private string $name = 'migrate';

    private const MIGRATIONS_TABLE = 'migrations';

    public function __construct(
        private Connection $connection,
        private string $migrationsPath
    ) {
    }

    public function execute(array $parameters = []): int
    {
        try {
            // 1. Создать таблицу миграций (migrations), если таблица еще не существует

            $this->createMigrationsTable();

            // 2. Получить $appliedMigrations (миграции, которые уже есть в таблице migrations)

            $appliedMigrations = $this->getAppliedMigrations();

            // 3. Получить $migrationFiles из папки миграций

            $migrationFiles = $this->getMigrationFiles();

            // 4. Получить миграции для применения

            $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

            $schema = new Schema();

            foreach ($migrationsToApply as $migration) {
                $migrationInstance = require $this->migrationsPath."/$migration";

                // 5. Создать SQL-запрос для миграций, которые еще не были выполнены

                $migrationInstance->up($schema);

                // 6. Добавить миграцию в базу данных

                $this->addMigration($migration);
            }

            // 7. Выполнить SQL-запрос

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

        } catch (\Throwable $e) {
            throw $e;
        }

        return 0;
    }

    private function createMigrationsTable(): void
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (! $schemaManager->tablesExist(self::MIGRATIONS_TABLE)) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE);
            $table->addColumn('id', Types::INTEGER, [
                'unsigned' => true,
                'autoincrement' => true,
            ]);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('created_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP',
            ]);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            echo 'Migrations table created'.PHP_EOL;
        }
    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATIONS_TABLE)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        $filteredFiles = array_filter($migrationFiles, function ($fileName) {
            return ! in_array($fileName, ['.', '..']);
        });

        return array_values($filteredFiles);
    }

    private function addMigration(string $migration): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS_TABLE)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }
}
