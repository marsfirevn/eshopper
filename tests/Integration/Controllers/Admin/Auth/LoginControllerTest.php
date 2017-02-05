<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 07/02/2017
 * Time: 22:11
 */

namespace Testing\Intergration\Controllers\Admin\Auth;

use Testing\AdminTestCase;

class LoginControllerTest extends AdminTestCase
{
    /**
     * Test client can get authentication
     */
    public function test_can_get_authentication()
    {
        $this->get("{$this->getBaseUrl()}/auth");
        $this->assertResponseOk();
        $this->isJson();
        $this->seeJsonStructure(['loggedIn', 'user']);
    }
}
