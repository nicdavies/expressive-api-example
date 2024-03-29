<?php

declare(strict_types=1);

namespace User\Handler;

use Exception;
use User\Entity\User;
use User\Service\UserService;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\ProblemDetails\ProblemDetailsResponseFactory;

/**
 * Class DeleteHandler
 * @package User\Handler
 */
class DeleteHandler implements RequestHandlerInterface
{
    /** @var UserService $userService */
    private $userService;

    /** @var ProblemDetailsResponseFactory $problemDetailsResponseFactory */
    private $problemDetailsResponseFactory;

    /**
     * DeleteHandler constructor.
     * @param UserService $userService
     * @param ProblemDetailsResponseFactory $problemDetailsResponseFactory
     */
    public function __construct(
        UserService $userService,
        ProblemDetailsResponseFactory $problemDetailsResponseFactory
    ) {
        $this->userService = $userService;
        $this->problemDetailsResponseFactory = $problemDetailsResponseFactory;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request) : ResponseInterface
    {
        /** @var User $user */
        $user = $request->getAttribute(User::class);

        try {
            $this->userService->delete($user);
        } catch (Exception $exception) {
            return $this->problemDetailsResponseFactory->createResponseFromThrowable(
                $request,
                $exception
            );
        }

        return new EmptyResponse(200);
    }
}
