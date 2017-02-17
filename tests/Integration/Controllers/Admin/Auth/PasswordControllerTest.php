<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 17/02/2017
 * Time: 21:21
 */

namespace Testing\Intergration\Controllers\Admin\Auth;

use App\Entities\Admin;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\AdminTestCase;

class PasswordControllerTest extends AdminTestCase
{
    use DatabaseTransactions;

    /**
     * Admin instance to test
     * @var Admin $admin
     */
    protected $admin;

    protected function setUp()
    {
        parent::setUp();
        $this->fakeAdmin();
    }

    /**
     * Test admin can get reset password form
     */
    public function test_admin_can_get_reset_password_form()
    {
        $this->get(route('admin.auth.password.getResetForm'));
        $this->assertResponseOk();
    }

    /**
     * Test admin can send request reset password, will receive reset password email
     */
    public function test_admin_can_send_request_reset_password()
    {
        $postedEmail = $this->post(route('admin.auth.password.postEmail'), ['email' => $this->admin->email]);
        $postedEmail->assertResponseOk()->isJson();
    }

    /**
     * Init admin instance
     */
    protected function fakeAdmin()
    {
        $this->admin = factory(Admin::class)->create();
    }
}
