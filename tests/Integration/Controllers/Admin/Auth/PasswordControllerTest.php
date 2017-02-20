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
use Password;
use Testing\AdminTestCase;

class PasswordControllerTest extends AdminTestCase
{
    use DatabaseTransactions;

    /**
     * Admin instance to test
     *
     * @var Admin $admin
     */
    protected $admin;
    protected $token;

    protected function setUp()
    {
        parent::setUp();
        $this->fakeAdmin();
        $this->fakeResetTokenAdmin();
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
        $postedEmail = $this->post(route('api.admin.auth.password.postEmail'), ['email' => $this->admin->email]);
        $postedEmail->assertResponseOk()->isJson();
    }

    /**
     * Test admin can change password via link reset password have been received in email
     */
    public function test_can_update_password_after_send_request_reset_password()
    {
        $postUpdatePassword = $this->makeResetPasswordData();
        $existsTokenData = array_only($postUpdatePassword, ['email', 'token']);

        $this->seeInDatabase($this->passwordsTable(), $existsTokenData);
        $changedPassword = $this->put(
            route('api.admin.auth.password.resetPassword', $this->token),
            $postUpdatePassword
        );
        $changedPassword->assertResponseOk()->isJson();
        $changedPassword->dontSeeInDatabase($this->passwordsTable(), $existsTokenData);
    }

    /**
     * Init admin instance
     */
    protected function fakeAdmin()
    {
        $this->admin = factory(Admin::class)->create();
    }

    /**
     * Fake reset token to test change password after send request reset password
     */
    protected function fakeResetTokenAdmin()
    {
        $this->token = Password::getRepository()->create($this->admin);
    }

    /**
     * @return array
     */
    protected function makeResetPasswordData()
    {
        $password = str_random(6);
        return [
            'email' => $this->admin->email,
            'token' => $this->token,
            'password' => $password,
            'password_confirmation' => $password,
        ];
    }

    /**
     * Get password reset table for current guard
     *
     * @return mixed
     */
    protected function passwordsTable()
    {
        $provider = config('auth.guards.' . $this->getGuard() . '.provider');
        return config('auth.passwords.' . $provider . '.table');
    }
}
