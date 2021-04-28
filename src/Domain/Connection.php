<?php


namespace App\Domain;


use PDO;

class Connection
{
    /**
     * @var string
     */
    private $databaseHost;
    /**
     * @var string
     */
    private $databasePort;
    /**
     * @var string
     */
    private $databaseName;
    /**
     * @var string
     */
    private $databaseUser;
    /**
     * @var string
     */
    private $databasePass;
    /**
     * @var array
     */
    private $pdoOptions;
    /**
     * @var array
     */
    private $pdoExecCommands;
    /**
     * @var PDO
     */
    private $pdo;

    public function __construct(
        string $databaseHost,
        string $databasePort,
        string $databaseName,
        string $databaseUser,
        string $databasePass,
        array $pdoOptions,
        array $pdoExecCommands
    ) {
        $this->databaseHost = $databaseHost;
        $this->databasePort = $databasePort;
        $this->databaseName = $databaseName;
        $this->databaseUser = $databaseUser;
        $this->databasePass = $databasePass;
        $this->pdoOptions = $pdoOptions;
        $this->pdoExecCommands = $pdoExecCommands;
    }

    private function connect()
    {
        $dsn = 'mysql:dbname=' . $this->databaseName . ';host=' . $this->databaseHost . '';
        $this->pdo = new PDO($dsn, $this->databaseUser, $this->databasePass, $this->pdoOptions);
        foreach ($this->pdoExecCommands as $command) {
            $this->pdo->exec($command);
        }
    }

    public function getPdo(): PDO
    {
        if (!$this->pdo) {
            $this->connect();
        }

        return $this->pdo;
    }

    public function lastInsertId(): ?int
    {
        return $this->getPdo()->lastInsertId();
    }
}