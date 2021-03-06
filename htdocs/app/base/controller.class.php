<?php

namespace Base;
use DI\Injectable;

/**
 * Base controller
 *
 * @throws InternalException
 */
abstract class Controller extends Injectable {

    private $data = [];

    /**
     * Load view
     * 
     * @example view('command') will use current Controller as resource
     * @example view('Controller', 'command')
     */
    public function view($command, $other = null) {

        // If request is ajax, we don't render any templates
        // For an ajax request, method is: view($data, $isSuccess);
        if ($this->request->isAjax()) {
            header('Content-Type: application/json; charset=utf-8');
            if (is_array($command)) {
                echo json_encode([
                    'success' => is_bool($other) ? $other : true,
                    'data' => $command
                ], JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    'success' => false,
                    'data' => null
                ], JSON_PRETTY_PRINT);
            }
            exit;
        }

        if (isset($other)) {
            $resource = $command;
            $command = $other;
        } else {
            $resource = get_class_name($this);
        }

        // Create View object
        $view = $this->di->create('View\\' . $resource);

        // Get avaliables (public and not inherited) methods
        $methods = get_class_methods($view);
        $base = get_class_methods('Base\\View');
        $avaliable = array_diff($methods, $base);

        if (in_array($command, $avaliable)) {

            // Pass data
            $view->with($this->data);
            
            // Execute command
            $view->$command();

            // Everything is OK
            return;
        }

        throw new \InternalException('View: ' . $resource . '->' . $command . '() is not defined');

    }

    // Set data variable
    protected function set($key, $value) {
        $this->data[$key] = $value;
        return $this;
    }

    // Get data variable
    protected function get($key) {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

}
