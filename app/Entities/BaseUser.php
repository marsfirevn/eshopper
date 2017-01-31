<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 23/01/2017
 * Time: 22:13
 */
namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Class BaseUser
 *
 * @package App\Core\Entities
 * @property int $id
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property bool $active
 * @property bool $verified
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
abstract class BaseUser extends Model implements
    UserProvider
{
    /**
     * Get type of user
     *
     * @return string
     */
    public function getType()
    {
        $className = static::class;

        return (substr($className, strrpos($className, '\\') + 1));
    }

    /**
     * Check is admin
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getType() == UserProvider::ADMIN;
    }

    /**
     * Check user is customer
     *
     * @return bool
     */
    public function isCustomer()
    {
        return $this->getType() == UserProvider::CUSTOMER;
    }

    /**
     * Get name of user
     *
     * @return string
     */
    public function getName()
    {
        return trim($this->first_name . ' ' . $this->last_name, ' ');
    }

    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->getName() ?: $this->email;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function getAvatarAttribute($value)
    {
        if (is_null($value)) {
            return config('common.default_avatar');
        }

        return Storage::url($value);
    }
}
