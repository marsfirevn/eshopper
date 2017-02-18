<?php

namespace Testing;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Contracts\Console\Kernel;
use Mockery;
use ReflectionClass;

/**
 * Class TestCase
 * @package Testing
 * @property $baseUrl string
 */
abstract class TestCase extends BaseTestCase
{
    protected $prefixDomain;
    protected $baseUrl = 'localhost';

    /**
     * Creates the application.
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();
        $this->setBaseUrl();

        return $app;
    }

    /**
     * Set application base URL.
     */
    protected function setBaseUrl()
    {
        $this->baseUrl = env('APP_DOMAIN', $this->baseUrl);
        if ($this->prefixDomain) {
            $this->baseUrl = 'http://' . $this->prefixDomain . '.' . $this->baseUrl;
        } else {
            $this->baseUrl = 'http://' . $this->baseUrl;
        }
    }

    /**
     * Get base url
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * Set auth user
     * @param $guard
     * @param array $attributes
     * @return mixed
     */
    public function setAuthUser($guard, array $attributes = [])
    {
        $entity = $this->getEntity($guard);
        $user = factory($entity)->create($attributes);
        $this->actingAs($user, $guard);

        return $user;
    }

    /**
     * Logout user
     * @param $guard
     */
    public function unsetAuthUser($guard)
    {
        \Auth::guard($guard)->logout();
    }

    /**
     * Get table name of user
     * @param $guard
     * @return null|string
     */
    protected function getEntity($guard)
    {
        $table = config('auth.guards.' . $guard . '.provider');
        return config('auth.providers.' . $table . '.model');
    }

    /**
     * Fake method for object
     * @param $object
     * @param $methodName
     * @param array $parameters
     * @return mixed
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    /**
     * Mock an instance for class
     * @param $class
     * @return mixed
     */
    public function mock($class)
    {
        $mock = Mockery::mock($class);
        app()->instance($class, $mock);

        return $mock;
    }
}
