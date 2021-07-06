<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageMeta extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'image_id',
        'author',
        'camera',
        'exif',
    ];
   
    /**
     * Author Accessor: Decode JSON value.
     *
     * @var string
     */

    public function getAuthorAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * Camera Accessor: Secode JSON value.
     *
     * @var string
     */

    public function getCameraAttribute($value)
    {
        return json_decode($value);
    }


    /**
     * EXIF Accessor: Secode JSON value.
     *
     * @var string
     */

    public function getExifAttribute($value)
    {
        return json_decode($value);
    }


    /**
     * Meat belongs to a question
     *
     * @return  \Illuminate\Database\Eloquent\Relations\belongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class);
    }
}
