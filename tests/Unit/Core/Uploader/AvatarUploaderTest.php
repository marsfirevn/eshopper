<?php
/**
 * Created by PhpStorm.
 * User: FRAMGIA\nguyen.huu.kim
 * Date: 04/01/2017
 * Time: 09:48
 */

namespace Unit\Core\Uploader;

use App\Core\Uploader\AvatarUploader;
use App\Entities\Admin;
use App\Entities\Teacher;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Storage;
use Testing\TestCase;

class AvatarUploaderTest extends TestCase
{
    use DatabaseTransactions;

    protected $localFile = __DIR__ . '/../../../../storage/files/test-avatar.jpg';
    protected $originFileName = 'test-avatar.jpg';
    protected $uploadedFile;
    protected $user;
    protected $userType = Admin::class;
    protected $table = 'admins';

    /**
     * @var AvatarUploader
     */
    protected $avatarUploader;

    protected function setUp()
    {
        parent::setUp();
        $this->initData();
    }

    /**
     * Test use can modify avatar
     */
    public function test_user_can_upload_new_avatar()
    {
        $result = $this->avatarUploader->make();
        $this->assertInstanceOf(Model::class, $this->user);
        $this->assertTrue(is_string($result) && Storage::exists($result));
        $this->seeInDatabase($this->table, ['avatar' => $result]);

        $oldAvatar = $this->user->avatar;
        $newAvatar = $this->avatarUploader->make();
        $this->assertTrue(! empty($oldAvatar), 'Old avatar not found in database');
        $this->assertTrue(is_string($newAvatar) && Storage::exists($newAvatar));
        $this->seeInDatabase($this->table, ['id' => $this->user->id, 'avatar' => $newAvatar]);
        $this->assertNotTrue(Storage::exists($oldAvatar));
    }

    /**
     * Make test data
     */
    protected function initData()
    {
        $this->user = factory($this->userType)->create();
        $this->uploadedFile = new UploadedFile($this->localFile, $this->originFileName, 'image/jpeg');
        $this->avatarUploader = new AvatarUploader($this->user, $this->uploadedFile);
    }
}
