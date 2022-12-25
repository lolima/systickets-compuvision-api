<?php

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;

$connectionParams = [
  'dbname' => getenv('DBNAME'),
  'user' => getenv('DBUSER'),
  'password' => getenv('DBPASS'),
  'host' => getenv('DBHOST'),
  'port' => getenv('DBPORT'),
  'driver' => 'pdo_mysql',
  "sslmode" => "require",
  "charset" => "utf8"
];

$conn = DriverManager::getConnection($connectionParams, new Configuration);


class DatabaseInteractor
{

  public static function setupConnection()
  {
    $connectionParams = [
      'dbname' => getenv('DBNAME'),
      'user' => getenv('DBUSER'),
      'password' => getenv('DBPASS'),
      'host' => getenv('DBHOST'),
      'port' => getenv('DBPORT'),
      'driver' => 'pdo_mysql',
      "sslmode" => "require",
      "charset" => "utf8"
    ];

    return DriverManager::getConnection($connectionParams, new Configuration);
  }
}

class CustomDatabaseInteractor
{

  /*  
     * Atributo estático para instância do PDO  
     */
  private static $pdo;

  /*  
     * Escondendo o construtor da classe  
     */
  private function __construct()
  {
    //  
  }

  /*  
     * Método estático para retornar uma conexão válida  
     * Verifica se já existe uma instância da conexão, caso não, configura uma nova conexão  
     */
  public static function getInstance()
  {
    if (!isset(self::$pdo)) {
      try {
        $connectionParams = [
          'dbname' => getenv('DBNAME'),
          'user' => getenv('DBUSER'),
          'password' => getenv('DBPASS'),
          'host' => getenv('DBHOST'),
          'port' => getenv('DBPORT'),
          'driver' => 'pdo_mysql',
          "sslmode" => "require",
          "charset" => "utf8"
        ];
        self::$pdo =  DriverManager::getConnection($connectionParams, new Configuration);
      } catch (PDOException $e) {
        print "Erro: " . $e->getMessage();
      }
    }
    return self::$pdo;
  }
}
