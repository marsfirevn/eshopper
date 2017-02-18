<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 07/02/2017
 * Time: 22:11
 */

namespace Testing\Integration\Controllers\Admin\Auth;

use App\Entities\Admin;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\AdminTestCase;

class LoginControllerTest extends AdminTestCase
{
    use DatabaseTransactions;

    /**
     * Admin instance to test log in
     * @var Admin $admin
     */
    protected $admin;
    protected $rawPassword = 'admin123';

    protected function setUp()
    {
        parent::setUp();
        $this->fakeAdmin();
    }

    /**
     * Test client can get authentication
     */
    public function test_can_get_authentication()
    {
        $this->get("{$this->getBaseUrl()}/auth");
        $this->assertResponseOk()->isJson();
        $this->seeJsonStructure(['loggedIn', 'user']);
    }

    /**
     * Test admin can login into dashboard
     */
    public function test_admin_can_login()
    {
        $credentials = $this->makePostLoginData();
        $loggedIn = $this->post(route('admin.auth.postLogin'), $credentials);
        $loggedIn->assertResponseOk()->isJson();
        $loggedIn->seeIsAuthenticated($this->getGuard());
        $loggedIn->seeJsonStructure([
            'user' => ['id', 'email', 'first_name', 'last_name', 'avatar'],
        ]);

        $credentialInvalidEmail = $this->makePostLoginData($randomEmail = true);
        $notExistsEmail = $this->post(route('admin.auth.postLogin'), $credentialInvalidEmail);
        $notExistsEmail->assertResponseStatus(422)->isJson();
        $notExistsEmail->seeJsonStructure(['error']);

        $this->blockCurrentAdmin();
        $accountWasBlocked = $this->post(route('admin.auth.postLogin'), $credentials);
        $accountWasBlocked->assertResponseStatus(403)->isJson();
        $accountWasBlocked->seeJsonStructure(['error']);
        $accountWasBlocked->dontSeeIsAuthenticated($this->getGuard());
    }

    /**
     * Block admin account
     */
    protected function blockCurrentAdmin()
    {
        $this->admin->is_active = false;
        $this->admin->save();
    }

    /**
     * Make post login data
     * @param bool $randomEmail
     * @return array
     */
    protected function makePostLoginData($randomEmail = false)
    {
        $faker = Factory::create();
        return [
            'email' => $randomEmail ? $faker->email : $this->admin->email,
            'password' => $this->rawPassword,
        ];
    }

    /**
     * Fake admin account into database
     */
    protected function fakeAdmin()
    {
        $this->admin = factory(Admin::class)->create(['password' => bcrypt($this->rawPassword)]);
    }
}
