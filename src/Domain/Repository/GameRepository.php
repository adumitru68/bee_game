<?php


namespace App\Domain\Repository;


use App\Domain\Entity\GameEntity;
use PDO;

class GameRepository extends AbstractRepository
{

    public function insert(int $userId)
    {
        $sql = '
            INSERT INTO `game` SET `userId` = :userId
        ';
        $this->execute($sql, ['userId' => $userId]);

        return $this->lastInsertId();
    }

    public function hits($gameId)
    {
        $sql = '
            UPDATE `game`
            SET game.hits = game.hits + 1
            WHERE game.id = :gameId
        ';
        $this->execute($sql, ['gameId' => $gameId]);
    }

    public function endGame(int $gameId)
    {
        $sql = 'UPDATE `game` SET `ended` = 1 WHERE game.id = :gameId';
        $this->execute($sql, ['gameId' => $gameId]);
    }

    /**
     * @param int $userId
     * @return GameEntity[]
     */
    public function getUserGames(int $userId): array
    {
        $sql = '
            SELECT 
                g.`id`,
                g.`userId`,
                g.`ended`,
                g.`hits`,
                u.`fullName` AS `UserName`
            FROM game g 
            JOIN user u on u.id = g.userId
            WHERE g.userId = :userId
        ';

        return $this->execute($sql, ['userId' => $userId])->fetchAll(PDO::FETCH_CLASS, GameEntity::class);
    }

    public function getById($gameId): ?GameEntity
    {
        $sql = 'SELECT * FROM game WHERE game.id = :gameId';
        return $this->execute($sql, ['gameId' => $gameId])->fetchObject(GameEntity::class) ?: null;
    }
}