<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 19/02/2017
 * Time: 15:31
 */

namespace Testing\Integration\Controllers\Admin;

use Testing\AdminTestCase;

class AdminControllerTest extends AdminTestCase
{
    public function test_can_get_admin_list()
    {
        $this->setAuthUser($this->getGuard());
        $this->get(route('api.admin.admin.list'));
        $this->assertResponseOk()->isJson();
        $this->seeJsonStructure([
            'message',
            'admins' => [
                '*' => ['first_name', 'last_name', 'email', 'avatar'],
            ],
        ]);
    }
}
