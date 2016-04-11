<?php

use DI\Container;
use Model\Service\Request;

/**
 * Main Application
 *
 * Starts the application and follow request
 *
 * @var $container The dependency injection container
 * @throws InternalException
 */
class App {

    private $container;

    // All stuff that need to be run before application is here
    public function start(Container $container, array $config) {

        $dbType = $config['database']['use'];
        $dbConfig = isset($config['database']['avaliable'][ $dbType ]) ? $config['database']['avaliable'][ $dbType ] : null;

        if (!empty($dbType) and !empty($dbConfig)) {
            // Share Database
            $dbclass = 'Database\\' . $dbType;
            $database = new $dbclass($dbConfig);
            $container->share('Database', $database);
        }

        // Start Session
        $session = $container->get('Session');
        $session->start();

        // Facebook
        $container->share('Facebook', function () {
            return new \Facebook\Facebook([
                'app_id' => '1372343316125328',
                'app_secret' => 'a1f81f07cfc0e5e9e2958e5131fd2d6b',
                'default_graph_version' => 'v2.2',
            ]);
        });

        $this->container = $container;
        
    }

    // And here we follow the given request
    public function follow(Request $request) {

        // Path is like:
        // Resource/Command/Args...
        $resource = $request->getResource();
        $command  = $request->getCommand();


        $controllerName = 'Controller\\' . ucfirst($resource);

        try {
            $controller = new $controllerName($this->container);
        } catch (\Exception $e) {
            $controller = null;
        }

        // Controller exists
        if (isset($controller)) {

            $methods = get_class_methods($controller);
            $base = get_class_methods('Base\\Controller');
            $avaliable = array_diff($methods, $base);

            // Get normal method
            if (in_array($command, $avaliable)) {

                // Just insert $request
                $view = $controller->$command($request);

                // If $controller didn't called a specific view, we call the default one
                $controller->view($command);

                // And stop script here
                exit;
            }

        }

        // Avoid looping in error
        if ($resource !== 'error') {
            $request->setRoute(['error']);
            $this->follow($request);

        // Print error
        } else {
            throw new \InternalException('Missing controller: "' . ucfirst($resource) . '" and method: "' . $command . '"');
        }

    }

}
