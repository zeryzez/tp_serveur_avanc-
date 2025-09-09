<?php

namespace jira\core\application\ports\api\dtos;

use jira\core\domain\entities\UserStoryStatus;

class ChangeStatusDTO {

    private UserStoryStatus $newStatus;

    public function __construct(UserStoryStatus $newStatus) {
        $this->newStatus = $newStatus;
    }

    public function getNewStatus(): UserStoryStatus {
        return $this->newStatus;
    }
}