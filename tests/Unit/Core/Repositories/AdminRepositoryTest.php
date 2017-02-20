<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 19/02/2017
 * Time: 08:24
 */

namespace Testing\Unit\Core\Repositories;

use App\Core\Repositories\AdminRepository;
use App\Core\Repositories\Exceptions\RepositoryException;
use App\Core\Repositories\Repository;
use App\Entities\Admin;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Testing\TestCase;

class AdminRepositoryTest extends TestCase
{
    /**
     * @var AdminRepository
     */
    protected $adminRepository;
    protected $admin;
    protected $entityName = Admin::class;

    protected function setUp()
    {
        parent::setUp();
        $this->adminRepository = app(AdminRepository::class);
        $this->fakeDatabase();
    }

    public function test_init_repository()
    {
        // Backup $adminRepository:
        $backup = $this->adminRepository;

        // Check exception: The repository has not property 'modelName':
        $this->repository_has_not_model_name_property_will_throw_exception();

        // Check exception: The repository does not instance of Eloquent\Model:
        $this->repository_has_model_does_not_instance_of_eloquent_will_throw_exception();

        // Restore $adminRepository:
        $this->adminRepository = $backup;
    }

    public function test_can_find_by_id()
    {
        // Find successfully:
        $admin = $this->adminRepository->findById($this->admin->id);
        $this->assertInstanceOf(Admin::class, $admin);
        $this->assertTrue($admin->id == $this->admin->id);

        // Find not found throw exception:
        try {
            $this->adminRepository->findById(1);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        }
    }

    /**
     * Find an entity instance matches by table field in database.
     * By default, if not found record which throw ModelNotFoundException.
     */
    public function test_can_find_entity_by_field()
    {
        // Find by success:
        $resultSuccess = $this->adminRepository->findBy('email', $this->admin->email);
        $this->assertInstanceOf($this->entityName, $resultSuccess);
        $this->assertEquals($this->admin->email, $resultSuccess->email);

        // Find not found will throw exception by default:
        try {
            $this->adminRepository->findBy('email', 'not_found@example.com');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        }

        // Find not found will return null, not exception:
        $nullResult = $this->adminRepository->findBy('email', 'not_found@example.com');
        $this->assertNull($nullResult);
    }

    /**
     * Test get all admin record
     */
    public function test_can_get_all_admins()
    {
        $admins = $this->adminRepository->all();
        $this->assertInstanceOf(Collection::class, $admins);
        $this->assertTrue($admins->count() >= 0);
    }

    /**
     * Test get by page
     */
    public function test_can_get_admin_by_page()
    {
        $admins = $this->adminRepository->paginate($perPage = 10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $admins);
        $this->assertTrue($admins->perPage() == $perPage);
    }

    /**
     * Repository can insert an entity into database
     * @test
     */
    public function can_create_entity()
    {
        // Test successfully:
        $data = $this->makeAdminData();
        $data['password'] = bcrypt('secret');

        /**
         * Admin instance
         * @var Admin $newObj
         */
        $newObj = $this->adminRepository->create($data);
        $this->assertInstanceOf($this->entityName, $newObj);
        $this->seeInDatabase('admins', ['email' => $newObj->email]);

        // Test Failed:
        try {
            $this->adminRepository->create(['k' => 1, 'dafsfds' => 'fdsafdsa']);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(QueryException::class, $exception);
        }
    }

    /**
     * Can update admin
     */
    public function test_record_can_be_update()
    {
        /**
         * @var Admin $updatedAdmin
         */
        $updateData = $this->makeAdminData();
        $updated = $this->adminRepository->update($updateData, $this->admin->id, $where = 'id');
        $this->assertTrue(is_numeric($updated));
    }

    /**
     * Test delete admin
     */
    public function test_record_can_be_delete()
    {
        $this->fakeDatabase();
        $deleted = $this->adminRepository->delete($this->admin->id);
        $this->dontSeeInDatabase('admins', ['id' => $this->admin->id]);
        $this->assertTrue(is_numeric($deleted));
    }

    /**
     * Fake list admin
     */
    private function fakeDatabase()
    {
        $this->admin = factory(Admin::class)->create();
    }

    /**
     * Check the repository has property: $entityName will throw exception.
     */
    private function repository_has_not_model_name_property_will_throw_exception()
    {
        try {
            $this->getMockForAbstractClass(Repository::class);
        } catch (\Exception $exception) {
            $this->assertInstanceOf(RepositoryException::class, $exception);
        }
    }

    /**
     * Check the repository has an entity model does not instance of Eloquent.
     */
    private function repository_has_model_does_not_instance_of_eloquent_will_throw_exception()
    {
        $reflection = new \ReflectionClass($this->adminRepository);
        $prop = $reflection->getProperty('modelName');
        $prop->setAccessible(true);
        $prop->setValue($this->adminRepository, self::class);
        try {
            $this->adminRepository->__construct();
        } catch (\Exception $exception) {
            $this->assertInstanceOf(RepositoryException::class, $exception);
        }
    }

    /**
     * Make admin data to test create
     * @return array
     */
    private function makeAdminData()
    {
        return factory($this->entityName)->make()->toArray();
    }
}
