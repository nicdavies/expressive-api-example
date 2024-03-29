<?php

declare(strict_types=1);

namespace User\Handler;

use User\Service\UserService;
use Psr\Container\ContainerInterface;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

/**
 * Class DeleteHandlerFactory
 * @package User\Handler
 */
class DeleteHandlerFactory
{
    /**
     * @param ContainerInterface $container
     * @return DeleteHandler
     */
    public function __invoke(ContainerInterface $container) : DeleteHandler
    {
        $userService = $container->get(UserService::class);
        $problemDetailsResponseFactory = $container->get(ProblemDetailsResponseFactory::class);

        return new DeleteHandler($userService, $problemDetailsResponseFactory);
    }
}
