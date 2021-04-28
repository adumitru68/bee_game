<?php


namespace App\Domain\Repository;


use App\Domain\Entity\UserEntity;
use PDO;

class UserRepository extends AbstractRepository
{
    /**
     * @return UserEntity[]
     */
    public function get(): array
    {
        $sql = 'SELECT * FROM user LIMIT 100';
        return $this->execute($sql, [])->fetchAll(PDO::FETCH_CLASS, UserEntity::class);
    }

    public function getById(int $id): ?UserEntity
    {
        $sql = 'SELECT * FROM user WHERE id = :id';
        return $this->execute($sql, ['id' => $id])->fetchObject(UserEntity::class) ?: null;
    }

    public function insert(string $fullName): int
    {
        $sql = 'INSERT INTO user SET fullName = :fullName';
        $this->execute($sql, ['fullName'=>$fullName]);

        return $this->getPdo()->lastInsertId();
    }
}