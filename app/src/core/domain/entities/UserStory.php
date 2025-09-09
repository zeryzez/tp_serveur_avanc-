<?php

namespace jira\core\domain\entities;

use jira\core\domain\exceptions\StatusChangeNotAllowedException;
use Ramsey\Uuid\Uuid;

class UserStory {
    private string $id;
    private string $title;
    private string $description;
    private UserStoryStatus $status;
    private Owner $owner;

    public function __construct(string $id, string $title, string $description, $status = UserStoryStatus::TODO) {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
    }

    public function changeStatus(UserStoryStatus $newStatus): void {
        if (!$this->isStatusChangeAllowed($newStatus)) {
            throw new StatusChangeNotAllowedException("Cannot change state from " . $this->status->value . " to " . $newStatus->value);
        }
        $this->status = $newStatus;
    }

    public function assignTo(Owner $owner) {
        $this->owner = $owner;
    }

    private function isStatusChangeAllowed(UserStoryStatus $newStatus): bool {
        return !($newStatus == UserStoryStatus::TODO || 
                    ($newStatus == UserStoryStatus::WIP && $this->status != UserStoryStatus::TODO) || 
                    ($newStatus == UserStoryStatus::DONE && $this->status != UserStoryStatus::WIP));
    }

    public function getId(): string {
        return $this->id;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function getDescription(): string {
        return $this->description;
    }

    public function getOwner(): Owner {
        return $this->owner;
    }

    public function getStatus(): UserStoryStatus {
        return $this->status;
    }
}