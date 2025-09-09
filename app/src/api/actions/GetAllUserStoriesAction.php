<?php

namespace jira\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteContext;

use jira\core\application\ports\api\UserStoryServiceInterface;

class GetAllUserStoriesAction extends AbstractAction {
    
    protected UserStoryServiceInterface $userStoryService;

    public function __construct(UserStoryServiceInterface $userStoryService) {
        $this->userStoryService = $userStoryService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $userStories = $this->userStoryService->getAllUserStories();
        $userStoriesArray = array_map(function($userStory) {
            return [
                'id' => $userStory->getId(),
                'title' => $userStory->getTitle(),
                'description' => $userStory->getDescription(),
                'status' => $userStory->getStatus(),
                'owner' => [
                    'userId' => $userStory->getOwner()->getUserId(),
                    'username' => $userStory->getOwner()->getUsername()
                ]
            ];
        }, $userStories);

        $rs->getBody()->write(json_encode($userStoriesArray));
        return $rs->withHeader('Content-Type', 'application/json');
    }

}

