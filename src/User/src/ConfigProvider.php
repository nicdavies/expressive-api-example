<?php

declare(strict_types=1);

namespace User;

use Zend\Expressive\Hal\Metadata\MetadataMap;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;
use Zend\Expressive\Hal\Metadata\RouteBasedResourceMetadata;

/**
 * Class ConfigProvider
 * @package User
 */
class ConfigProvider
{
    /**
     * @return array
     */
    public function __invoke() : array
    {
        return [
            'dependencies'     => $this->getDependencies(),
            'routes'           => $this->getRoutes(),
            'doctrine'         => $this->getDoctrine(),
            MetadataMap::class => $this->getHalMetaDataMap(),
        ];
    }

    /**
     * @return array
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
                Filter\UserFilter::class => Filter\UserFilter::class,
                Hydrator\UserHydrator::class => Hydrator\UserHydrator::class,
            ],

            'factories' => [
                Service\UserService::class => Service\UserServiceFactory::class,
                Handler\FetchHandler::class => Handler\FetchHandlerFactory::class,
                Handler\CreateHandler::class => Handler\CreateHandlerFactory::class,
                Handler\DeleteHandler::class => Handler\DeleteHandlerFactory::class,
                Handler\UpdateHandler::class => Handler\UpdateHandlerFactory::class,
                Middleware\UserFetchMiddleware::class => Middleware\UserFetchMiddlewareFactory::class,
            ],
        ];
    }

    /**
     * @return array
     */
    public function getRoutes() : array
    {
        return [
            [
                'name'            => 'user.create',
                'path'            => '/user',
                'middleware'      => Handler\CreateHandler::class,
                'allowed_methods' => ['POST'],
            ],
            [
                'name'            => 'user.fetch',
                'path'            => '/user/{id}',
                'middleware'      => Handler\FetchHandler::class,
                'allowed_methods' => ['GET'],
            ],
            [
                'name'            => 'user.update',
                'path'            => '/user/{id}',
                'middleware'      => [
                    Middleware\UserFetchMiddleware::class,
                    Handler\UpdateHandler::class,
                ],
                'allowed_methods' => ['PUT'],
            ],
            [
                'name'            => 'user.delete',
                'path'            => '/user/{id}',
                'middleware'      => [
                    Middleware\UserFetchMiddleware::class,
                    Handler\DeleteHandler::class,
                ],
                'allowed_methods' => ['DELETE'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getDoctrine() : array
    {
        return [
            'driver' => [
                'user_driver' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => __DIR__ . '/Entity',
                ],

                'orm_default' => [
                    'drivers' => [
                        'User\Entity' => 'user_driver',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getHalMetaDataMap() : array
    {
        return [
            [
                '__class__'      => RouteBasedResourceMetadata::class,
                'resource_class' => Entity\User::class,
                'route'          => 'user.fetch',
                'extractor'      => Hydrator\UserHydrator::class,
            ],
        ];
    }
}
