<?php


namespace App\Domain\Repository;


use App\Domain\Entity\BeeEntity;
use PDO;

class BeeRepository extends AbstractRepository
{
    /**
     * @param int $gameId
     * @return BeeEntity[]
     */
    public function getSwarm(int $gameId):array
    {
        $sql = 'SELECT * FROM bee WHERE gameId = :gameId';
        return $this->execute($sql, ['gameId' => $gameId])->fetchAll(PDO::FETCH_CLASS, BeeEntity::class);
    }

    public function hits(int $beeId)
    {
        $sql = '
            UPDATE bee 
            SET bee.healthyRemain = GREATEST(0, bee.healthyRemain - bee.damageRate)
            WHERE id = :beeId
        ';
        $this->execute($sql, ['beeId'=>$beeId]);
    }

    public function insert(int $gameId, int $beeType, int $healthyPoints, int $damageRate)
    {
        $sql = 'INSERT INTO bee
            SET 
                `gameId` = :gameId, 
                `beeType` = :beeType,
                `healthyPoints` = :healthyPoints,
                `healthyRemain` = :healthyPoints,
                `damageRate` = :damageRate
        ';

        $placeholderParams = [
            'gameId' => $gameId,
            'beeType' => $beeType,
            'healthyPoints' => $healthyPoints,
            'damageRate' => $damageRate,
        ];

        $this->execute($sql, $placeholderParams);
    }
}