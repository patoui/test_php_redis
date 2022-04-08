<?php

declare(strict_types=1);

namespace Patoui\TestPhpRedis\Repositories;

use PDO;
use Patoui\TestPhpRedis\DataTransferObjects\NewTransaction;

class AccountRepository
{
    private PDO $pdo;

    public function __construct(?PDO $pdo = null)
    {
        $this->pdo = $pdo ?? db();
    }

//    /**
//     * Update the current user
//     * @param Account        $account
//     * @param NewTransaction $new_transaction
//     * @return void
//     */
//    public function update(Account $account, NewTransaction $new_transaction): void
//    {
//        $this->pdo->beginTransaction();
//        $update_statement = $this->pdo->prepare('UPDATE account SET balance = ? WHERE id = ?');
//        $update_statement->execute([
//            $new_transaction->amount,
//            $account->id,
//        ]);
//        if ($this->pdo->inTransaction()) {
//            if ($this->pdo->commit()) {
//
//            }
//        }
//    }
}