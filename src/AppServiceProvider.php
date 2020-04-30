<?php

namespace NookPlus;

use Dotenv\Dotenv;
use NookPlus\ActionResolver;
use Pimple\Container;
use Pimple\Psr11\Container as PsrContainer;
use Pimple\ServiceProviderInterface;
use Predis\Client;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Interfaces\CallableResolverInterface;

class AppServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container)
    {
        // Slim
        $container[CallableResolverInterface::class] = fn($c) => new ActionResolver($c);
        $container[App::class] = fn($c) => $this->getApp($c);

        // Configuration
        $container['env'] = function ($c) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
            return $dotenv->load();
        };

        // Data
        $container[Client::class] = fn($c) => new Client('tcp://' . $c['env']['REDIS_HOST'] . ':6379');
        $container[Data::class] = fn($c) => new Data($c[Client::class]);

        // Actions
        $container[CreateUserAction::class] = fn($c) => new CreateUserAction($c[Data::class]);
        $container[GetUserAction::class] = fn($c) => new GetUserAction($c[Data::class]);
        $container[AddValueActions::class] = fn($c) => new AddValuesAction($c[Data::class]);
        $container[AddValueAction::class] = fn($c) => new AddValueAction($c[Data::class]);
        $container[RemoveValueAction::class] = fn($c) => new RemoveValueAction($c[Data::class]);
    }

    private function getApp(Container $container)
    {
        $app = AppFactory::createFromContainer(new PsrContainer($container));

        $app->post('/users', CreateUserAction::class);
        $app->get('/users/:id', GetUserAction::class);
        $app->post('/users/:id/:key', AddValuesAction::class);
        $app->put('/users/:id/:key/:value', AddValueAction::class);
        $app->delete('/users/:id/:key/:value', RemoveValueAction::class);

        return $app;
    }
}
