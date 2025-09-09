<?php

namespace jira\core\application\ports\spi;

use jira\core\domain\entities\UserStory;
use jira\core\application\exceptions\UserStoryNotFoundException;
use Ramsey\Uuid\Uuid;

interface UserStoryRepository {

    /**
     * @return UserStory[]
     */
    public function findAll(): array;

    /**
     * @return UserStory
     * @throws UserStoryNotFoundException
     */
    public function findById(string $id): UserStory;

    /**
     */
    public function save(UserStory $userStory): void;
}