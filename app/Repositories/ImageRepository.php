<?php

namespace App\Repositories;
use App\Models\Image;
use App\Repositories\ExifRepository;
use Illuminate\Support\Facades\Storage;
use Image as InterventionImage;

class ImageRepository extends ExifRepository
{

    protected $image;


    public function __construct(Image $image)
    {
        $this->image = $image;
    } 


    public function get($request)
    {
        return $this->image->with('meta')->latest()->paginate(10);
    }



    public function store($file, $type = null)
    {
        try{

            $img  = InterventionImage::make($file);

            if($type == 'url'){
                // get directory with file name from url
                $this->image->src   = $this->imagePath($this->getExtension($file));
                // Extract exif information from url
                $this->exif  = exif_read_data($file);



            }else{

                $this->image->src   = $this->imagePath($file->getClientOriginalExtension());
                // Extract exif information from fille
                $this->exif =  $img->exif();
            }
            // store image to directory
            $img->save($this->image->src);
            //Storage::put($this->image->src, $img->encode());


            

            // store image information
            $this->image->height     = $img->height();
            $this->image->width      = $img->width();
            $this->image->mime_type  = $img->mime();
            $this->image->save();


            if($this->exif){
                if($this->exif['SectionsFound'] != ""){

                    // process meta data
                    $this->processMeta();

                    // store image meta
                    $this->image->meta()->create([
                        'camera' => json_encode($this->camera),
                        'author' => json_encode($this->author),
                        'exif'   => json_encode($this->exifMeta)
                    ]);
                }

            }

            //return $this->image;
            return $this->image->with('meta')->find($this->image->id);

        }catch(\Exception $e){
            return $e->getMessage();

        }
    }


    public function find($id)
    {
        return $this->image->with('meta')->find($id);
    }

    



    public function processMeta()
    {
        $this->exif = (object)$this->encodeExifToUtf8($this->exif);
        $this->extractInformation();
        
    }


    /**
     * return image directory with file name to store image file
     * 
     * @return  images/current-month/unique-id.extension
     */

    public function imagePath($extension)
    {
        return public_path('images/'.date('Y-m').'/'.uniqid().'.'.$extension);
    }

    /**
     * fetch file extension from url
     * 
     * @return  extension
     */

    public function getExtension($url, $type = 'file')
    {
        return last(explode(".",basename($url)));
    }

    
}