<?php

namespace jira\core\application\ports\api;

use jira\core\domain\entities\UserStory;
use jira\core\domain\entities\UserStoryStatus;
use jira\core\domain\exceptions\StatusChangeNotAllowedException;
use jira\core\application\exceptions\UserStoryNotFoundException;
use Ramsey\Uuid\Uuid;

interface UserStoryServiceInterface {

    /**
     * @throws UserStoryNotFoundException
     * @throws StatusChangeNotAllowedException
     */
    public function changeStatus(string $id, UserStoryStatus $newStatus): void;

    /**
     * @return UserStory[]
     */
    public function getAllUserStories(): array;
}