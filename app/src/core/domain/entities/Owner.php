<?php

namespace jira\core\domain\entities;

use Ramsey\Uuid\Uuid;

class Owner {
    private string $userId;
    private string $username;

    public function __construct(string $userId, string $username) {
        $this->userId = $userId;
        $this->username = $username;
    }

    public function getUserId(): string {
        return $this->userId;
    }

    public function getUsername(): string {
        return $this->username;
    }
}