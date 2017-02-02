<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 02/02/2017
 * Time: 22:39
 */

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Category
 * @package App\Entities
 * @property int $id
 * @property string $name
 * @property string $description
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['name', 'description'];
    protected $casts = ['is_active' => 'bool'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
