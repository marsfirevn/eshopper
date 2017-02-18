<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 07/02/2017
 * Time: 22:07
 */

namespace Testing;

abstract class AdminTestCase extends TestCase
{
    protected $prefixDomain = 'admin';

    /**
     * Get guard name
     */
    protected function getGuard()
    {
        return 'admin';
    }
}
