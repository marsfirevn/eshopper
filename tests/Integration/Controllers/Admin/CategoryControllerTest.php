<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 18/02/2017
 * Time: 14:30
 */

namespace Testing\Integration\Controllers\Admin;

use Testing\AdminTestCase;

class CategoryControllerTest extends AdminTestCase
{
    /**
     * Test can get category list
     */
    public function test_can_get_category_list()
    {
        $limitPermission = $this->get(route('admin.category.list'));
        $limitPermission->assertResponseStatus(302);

        $this->setAuthUser($this->getGuard());
        $canGetCategories = $this->get(route('admin.category.list'));
        $canGetCategories->assertResponseOk();
    }
}
