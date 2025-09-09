<?php

use function PHPUnit\Framework\assertEquals;
use jira\core\domain\entities\UserStory;
use jira\core\domain\entities\UserStoryStatus;
use jira\core\domain\exceptions\StatusChangeNotAllowedException;

it("should create a user story with status TODO", function() {
    $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $userStory = new UserStory($uuid, "title", "description");
    assertEquals(UserStoryStatus::TODO, $userStory->getStatus());
});

it("should accept the change of a user story from TODO to WIP", function() {
    $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $userStory = new UserStory($uuid, "title", "description");
    $userStory->changeStatus(UserStoryStatus::WIP);
    assertEquals(UserStoryStatus::WIP, $userStory->getStatus());
});

it("should accept the change of a user story from WIP to DONE", function() {
    $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $userStory = new UserStory($uuid, "title", "description");
    $userStory->changeStatus(UserStoryStatus::WIP);
    $userStory->changeStatus(UserStoryStatus::DONE);
    assertEquals(UserStoryStatus::DONE, $userStory->getStatus());
});

it("should reject the change of a user story from TODO to DONE", function() {
    $uuid = \Ramsey\Uuid\Uuid::uuid4()->toString();
    $userStory = new UserStory($uuid, "title", "description");
    $userStory->changeStatus(UserStoryStatus::DONE);
})->throws(StatusChangeNotAllowedException::class, 'Cannot change state from TODO to DONE');

