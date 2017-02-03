<?php
/**
 * Created by PhpStorm.
 * User: huukimit
 * Date: 02/02/2017
 * Time: 22:41
 */

namespace App\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Product
 * @package App\Entities
 * @property int $id
 * @property string $name
 * @property int $price
 * @property string $thumbnail
 * @property array $images
 * @property string $description
 * @property bool $is_active
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Product extends Model
{
    protected $table = 'products';
    protected $fillable = [
        'name',
        'price',
        'thumbnail',
        'images',
        'description',
        'is_active',
    ];
    protected $casts = ['is_active' => 'bool'];

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}
