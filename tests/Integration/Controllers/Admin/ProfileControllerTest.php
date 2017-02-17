<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 16/02/2017
 * Time: 22:26
 */

namespace Testing\Integration\Controllers\Admin;

use App\Entities\Admin;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Testing\AdminTestCase;

class ProfileControllerTest extends AdminTestCase
{
    use DatabaseTransactions;

    protected $admin;

    /**
     * Set up test
     */
    protected function setUp()
    {
        parent::setUp();
        $this->admin = factory(Admin::class)->create();
    }

    /**
     * Test admin can update profile
     */
    public function test_admin_can_update_profile()
    {
        $postUpdateData = $this->makePostUpdateProfileData();
        $dataCheckAfterUpdate = array_except($postUpdateData, ['password', 'password_confirmation']);

        $notLoginCase = $this->put("{$this->getBaseUrl()}/profile", $postUpdateData);
        $notLoginCase->assertRedirectedTo(route('admin.auth.getLogin'));

        $this->setAuthUser('admin');
        $loggedIn = $this->put("{$this->getBaseUrl()}/profile", $postUpdateData);
        $loggedIn->assertResponseOk()->isJson();
        $loggedIn->seeInDatabase('admins', $dataCheckAfterUpdate);
    }

    /**
     * Make post update profile data to test
     * @return array
     */
    protected function makePostUpdateProfileData()
    {
        $faker = Factory::create();
        return [
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'password' => $password = str_random(3),
            'password_confirmation' => $password,
        ];
    }
}
