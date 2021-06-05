<?php

namespace App\Models;

use App\Models\ImageMeta;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'src',
        'actual_src',
        'height',
        'width',
        'mime_type'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at'
    ];


    /**
     * A image may have meta information
     *
     * @return  \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function meta()
    {
        return $this->hasOne(ImageMeta::class,'image_id','id');
    }


}
