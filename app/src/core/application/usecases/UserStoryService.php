<?php

namespace jira\core\application\usecases;

use jira\core\application\ports\api\UserStoryServiceInterface;
use jira\core\application\ports\spi\UserStoryRepository;
use jira\core\domain\entities\UserStory;
use jira\core\domain\entities\UserStoryStatus;
use jira\core\domain\exceptions\StatusChangeNotAllowedException;
use jira\core\application\exceptions\UserStoryNotFoundException;
use Ramsey\Uuid\Uuid;

class UserStoryService implements UserStoryServiceInterface {

    private UserStoryRepository $userStoryRepository;

    public function __construct(UserStoryRepository $userStoryRepository) {
        $this->userStoryRepository = $userStoryRepository;
    }

    /**
     * @return UserStory[]
     */
    public function getAllUserStories(): array {
        return $this->userStoryRepository->findAll();
    }

    /**
     * @throws UserStoryNotFoundException
     * @throws StatusChangeNotAllowedException
     */
    public function changeStatus(string $id, UserStoryStatus $newStatus): void {
        $userStory = $this->userStoryRepository->findById($id);
        $userStory->changeStatus($newStatus);
        $this->userStoryRepository->save($userStory);
    }
}