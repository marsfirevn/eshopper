<?php
namespace Unit\Core\Repositories;

use App\Core\Repositories\AdminRepository;
use App\Core\Repositories\Repository;
use App\Entities\Admin;
use DB;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Collection;
use Testing\TestCase;

class RepositoryTest extends TestCase
{
    use DatabaseTransactions;
    use WithoutMiddleware;

    protected $connectionsToTransact = ['mysql_test'];
    /**
     * Repository instance
     * @var AdminRepository $adminRepository
     */
    protected $adminRepository;
    protected $fakeData;
    protected $data;
    protected $entityName;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->setData();
        $this->entityName = Admin::class;
    }

    protected function setUp()
    {
        parent::setUp();
        $this->fakeDatabase();
        $this->adminRepository = new AdminRepository();
    }

    /**
     * Test init repository
     * @test makeEntity
     */
    public function init_repository_throw_exception()
    {
        // Backup $adminRepository:
        $backup = $this->adminRepository;

        // Check exception: The repository has not property 'entityName':
        $this->repository_has_not_entity_name_property_will_throw_exception();

        // Check exception: The repository does not instance of Eloquent\Model:
        $this->repository_has_entity_does_not_instance_of_eloquent_will_throw_exception();

        // Restore $adminRepository:
        $this->adminRepository = $backup;
    }

    /**
     * When use all(), this return instance of \Illuminate\Support\Collection which has array of entityName
     * or Collection of empty array.
     * @test all
     */
    public function should_get_all()
    {
        $allResult = $this->adminRepository->all();

        $this->assertInstanceOf(Collection::class, $allResult);
        $this->assertTrue($allResult->count() >= 1);

        $firstItem = $allResult->first();
        $this->assertInstanceOf($this->entityName, $firstItem);
    }

    /**
     * This is function which return entity instance matches id field.
     * If not found will throw ModelNotFoundException.
     * @test findById
     */
    public function can_find_entity_by_id()
    {
        // Find successfully:
        $admin = $this->adminRepository->findById($this->fakeData->id);
        $this->assertInstanceOf($this->entityName, $admin);
        $this->assertTrue($admin->id == $this->fakeData->id);

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
     * @test findBy
     */
    public function can_find_entity_by_field()
    {
        // Find by success:
        $resultSuccess = $this->adminRepository->findBy('email', $this->fakeData->email);
        $this->assertInstanceOf($this->entityName, $resultSuccess);
        $this->assertEquals($this->fakeData->email, $resultSuccess->email);

        // Find not found will throw exception by default:
        try {
            $this->adminRepository->findBy('email', 'not_found@example.com');
        } catch (\Exception $exception) {
            $this->assertInstanceOf(ModelNotFoundException::class, $exception);
        }

        // Find not found will return null, not exception:
        $nullResult = $this->adminRepository->findBy('email', 'not_found@example.com', $shouldThrowException = false);
        $this->assertNull($nullResult);
    }

    /**
     * Order result by a field. Result return sorted collection.
     * @test orderBy
     */
    public function repository_can_order_by_field()
    {
        $this->the_order_result_should_be_sorted('email', 'asc');
        $this->the_order_result_should_be_sorted('email', 'desc');
        $this->the_order_result_should_be_sorted('created_at', 'asc');
        $this->the_order_result_should_be_sorted('created_at', 'desc');
    }

    /**
     * The whereIn method verifies that a given column's value is contained within the given array
     * @test whereIn
     */
    public function can_be_give_column_value_is_contained_within_the_give_array()
    {
        $expectedArray = [1, 2, 3];
        $adminList = $this->adminRepository->whereIn('id', $expectedArray);
        $this->assertInstanceOf(Collection::class, $adminList);

        foreach ($adminList as $admin) {
            // Check where in $exceptedArray:
            $this->assertInstanceOf($this->entityName, $admin);
            $this->assertTrue(in_array($admin->id, $expectedArray));
        }
    }

    /**
     * Return instance of Collection by condition which can contain scientific symbols such as: >=, <=, <, >
     * @test where
     */
    public function test_should_get_list_matches_condition()
    {
        // Normal where:
        $admin = $this->adminRepository->where(['id' => $this->fakeData->id])->first();
        // Where clause with scientific symbols:
        $symbols = ['>=', '<=', '>', '<', '<>'];
        $admins = $this->adminRepository->where(['id' => ['id', $symbols[0], 2]]);

        // Check $admin:
        $this->assertInstanceOf($this->entityName, $admin);
        $this->assertEquals($this->fakeData->id, $admin->id);

        // Check $admins:
        $this->assertInstanceOf(Collection::class, $admins);
        foreach ($admins as $admin) {
            // Matches condition:
            $this->assertTrue($admin->id >= 2);
        }
    }

    /**
     * Repository can insert an entity into database
     * @test
     */
    public function can_create_entity()
    {
        // Test successfully:
        $data = $this->make_admin_array_data();
        $data['password'] = bcrypt('secret');

        $this->assertTrue(is_array($data));
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
     * Make admin data to test create
     * @return array
     */
    public function make_admin_array_data()
    {
        return factory($this->entityName)->make()->toArray();
    }

    /**
     * Check the repository has property: $entityName will throw exception.
     */
    public function repository_has_not_entity_name_property_will_throw_exception()
    {
        try {
            $this->getMockForAbstractClass(Repository::class);
        } catch (\Exception $exception) {
            $this->assertContains('must provide an attribute called', $exception->getMessage());
        }
    }

    /**
     * Check the repository has an entity model does not instance of Eloquent.
     */
    public function repository_has_entity_does_not_instance_of_eloquent_will_throw_exception()
    {
        $reflection = new \ReflectionClass($this->adminRepository);
        $prop = $reflection->getProperty('entityName');
        $prop->setAccessible(true);
        $prop->setValue($this->adminRepository, self::class);
        try {
            $this->adminRepository->__construct();
        } catch (\Exception $exception) {
            $this->assertContains(
                'must be an instance of Illuminate\\Database\\Eloquent\\Model',
                $exception->getMessage()
            );
        }
    }

    /**
     * Compare value for asc sorting. Return:
     * + Value -1: DESC
     * + Value 1: ASC
     * + Value 0: DESC, ASC (=)
     * @param $currValue
     * @param $nextValue
     * @param $fieldType
     * @return int
     */
    public function compare_two_adjacent_value_after_order_by_field($currValue, $nextValue, $fieldType)
    {
        // Check sort by string:
        if ($fieldType == 'string') {
            $check = strcasecmp($currValue, $nextValue);

            return $check <= 0 ? -1 : ($check > 0 ? 1 : 0);
        }

        // Check sort by number field:
        if (in_array($fieldType, ['integer', 'double', 'float'])) {
            return $currValue < $nextValue ? -1 : ($currValue > $nextValue ? 1 : 0);
        }

        // Check date time:
        if (in_array($fieldType, ['date', 'timestamp', 'datetime'])) {
            $currValue = strtotime($currValue);
            $nextValue = strtotime($nextValue);

            return $currValue < $nextValue ? -1 : ($currValue > $nextValue ? 1 : 0);
        }

        $this->throwException(new \Exception('Table field type "' . $fieldType . '" does not supported.'));
        return 0;
    }

    /**
     * Check order by field name
     * @param string $fieldName
     * @param string $sortType
     */
    public function the_order_result_should_be_sorted($fieldName = 'email', $sortType = 'asc')
    {
        $fieldType = DB::connection(env('DB_CONNECTION_TEST'))
            ->getDoctrineColumn('admins', $fieldName)
            ->getType()
            ->getName();
        $list = $this->adminRepository->orderBy($fieldName, $sortType)->get();
        $this->assertInstanceOf(Collection::class, $list);

        // Simple check sorted:
        foreach ($list as $index => $currItem) {
            if ($nextItem = $list->get($index + 1)) {
                $checkSort = $this->compare_two_adjacent_value_after_order_by_field(
                    $currItem->$fieldName,
                    $nextItem->$fieldName,
                    $fieldType
                );

                if ($sortType == 'asc') {
                    $this->assertTrue($checkSort <= 0);
                } else {
                    $this->assertTrue($checkSort >= 0);
                }
            }
        }
    }

    /**
     * @test create
     */
    public function test_record_can_be_created()
    {
        $admin = $this->adminRepository->create($this->data[0]);
        $this->assertInstanceOf($this->entityName, $admin);
        $this->assertTrue($this->checkData($this->data[0], $admin));
        $this->seeInDatabase('admins', $this->data[0]);
    }

    /**
     * @test update
     */
    public function test_record_can_be_update()
    {
        $admin = Admin::create($this->data[0]);
        $updatingData = $this->data[3];
        $updatingAdmin = array_merge($admin->toArray(), $updatingData);
        $updatedAdmin = $this->adminRepository->update($updatingAdmin, $admin->id, 'id');
        $this->assertTrue($this->checkData($updatingData, $updatedAdmin));
    }

    /**
     * @test updateOrCreate
     */
    public function test_record_can_be_updated_or_create_new()
    {
        $admin = $this->adminRepository->updateOrCreate($this->data[1]);
        $this->assertInstanceOf($this->entityName, $admin);
        $this->assertTrue($this->checkData($this->data[1], $admin));

        $updatingData = $this->data[3];
        $updatingAdmin = array_merge($admin->toArray(), $updatingData);
        $updatedAdmin = $this->adminRepository->updateOrCreate($admin->toArray(), $updatingAdmin);
        $this->assertTrue($this->checkData($updatingData, $updatedAdmin));
    }

    /**
     * @test delete
     */
    public function test_record_can_be_delete()
    {
        $admin = Admin::create($this->data[0]);
        $deletedObjectCount = $this->adminRepository->delete($admin);
        $this->assertGreaterThan(0, $deletedObjectCount);

        $admin = Admin::create($this->data[1]);
        $deletedObjectCount = $this->adminRepository->delete($admin->id);
        $this->assertGreaterThan(0, $deletedObjectCount);
    }

    /**
     * @test getLatestEntities
     */
    public function test_can_get_limited_latest_entities()
    {
        $limit = null;
        $eagerLoad = [];
        $paginator = $this->adminRepository->getLatestEntities($limit, $eagerLoad);
        $this->assertLessThanOrEqual(config('common.pagination_per_page'), $paginator->perPage());
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);

        $limit = 2;
        $paginator = $this->adminRepository->getLatestEntities($limit, $eagerLoad);
        $this->assertLessThanOrEqual($limit, $paginator->perPage());
        $this->assertInstanceOf(LengthAwarePaginator::class, $paginator);
    }

    /**
     * @test insert
     */
    public function test_one_record_can_be_inserted()
    {
        $recordCount = $this->adminRepository->insert($this->data[0]);
        $this->assertEquals(6, $recordCount);
        $this->seeInDatabase('admins', $this->data[0]);
    }

    /**
     * @test batchInsert
     */
    public function test_multiple_records_can_be_inserted()
    {
        $data = array_merge([], $this->data);
        unset($data[3]);
        $returnLastId = false;
        $oldCount = Admin::count();
        $this->adminRepository->batchInsert($data, $returnLastId);
        $this->assertEquals(3 + $oldCount, Admin::all()->count());
        $this->seeInDatabase('admins', $data[0]);
        $this->seeInDatabase('admins', $data[1]);
        $this->seeInDatabase('admins', $data[2]);

        Admin::destroy(Admin::all()->pluck('id')->toArray());
        $returnLastId = true;
        $lastId = $this->adminRepository->batchInsert($data, $returnLastId);
        $lastRow = Admin::orderBy('id', 'desc')->first();
        $this->assertEquals($lastId, $lastRow->id);
        $this->assertEquals(3, Admin::all()->count());
        $this->seeInDatabase('admins', $data[0]);
        $this->seeInDatabase('admins', $data[1]);
        $this->seeInDatabase('admins', $data[2]);
    }

    /**
     * check data between 2 mixed object
     * @param array data1
     * @param collection data2
     * @return boolean
     */
    private function checkData($data1, $data2)
    {
        unset($data1['password']);
        $data2 = $data2->toArray();

        foreach ($data1 as $key => $value) {
            if (! array_key_exists($key, $data2)) {
                return false;
            }

            if ($data1[$key] != $data2[$key]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Make a record save into database to test.
     */
    private function fakeDatabase()
    {
        if (Admin::all()->count() == 0) {
            $list = factory($this->entityName, 5)->create();
            $this->fakeData = $list->first();
        } else {
            $this->fakeData = Admin::first();
            $this->fakeData->created_at = '2017-01-03 15:00:00';
            $this->fakeData->save();
        }
    }

    /**
     * set data
     * @return void
     */
    private function setData()
    {
        $this->data = [
            [
                'email' => 'luong1@gmail.com',
                'password' => 'luong1',
                'is_super' => 1,
                'is_active' => 1,
            ],
            [
                'email' => 'luong2@gmail.com',
                'password' => 'luong2',
                'is_super' => 0,
                'is_active' => 1,
            ],
            [
                'email' => 'luong3@gmail.com',
                'password' => 'luong3',
                'is_super' => 0,
                'is_active' => 1,
            ],
            [
                'email' => 'luong1@gmail.com',
                'password' => 'luong4',
                'is_super' => 0,
                'is_active' => 0,
            ],
        ];
    }
}
