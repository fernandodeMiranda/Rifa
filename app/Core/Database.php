<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Conexão PDO única (singleton) reutilizada por todos os Repositories.
 */
final class Database
{
    private static ?PDO $instance = null;

    public static function connection(): PDO
    {
        if (self::$instance === null) {
            $config = require __DIR__ . '/../Config/database.php';

            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $config['driver'],
                $config['host'],
                $config['port'],
                $config['database'],
                $config['charset']
            );

            try {
                self::$instance = new PDO($dsn, $config['username'], $config['password'], [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ]);
            } catch (PDOException $e) {
                // TODO: registrar em storage/logs em vez de expor o erro cru.
                throw new PDOException('Falha ao conectar ao banco de dados: ' . $e->getMessage());
            }
        }

        return self::$instance;
    }
}
