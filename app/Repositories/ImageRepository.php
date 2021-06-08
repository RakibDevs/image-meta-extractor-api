<?php

namespace App\Repositories;
use App\Models\Image;
use App\Repositories\ExifRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

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
        return $this->image->with('meta')->latest()->paginate(9);
    }



    public function store($request)
    {
        try{

            if($request->type == 'url'){
                // get directory with file name from url
                $this->image->src   = $this->imagePath($this->getExtension($request->url));
                $this->image->actual_src   = $request->url;
                $img  = InterventionImage::make($request->url);
                // Extract exif information from url
                $this->exif  = exif_read_data($request->url);



            }else{
                $file = $request->file('image');
                $this->image->src   = $this->imagePath($file->getClientOriginalExtension());
                $img  = InterventionImage::make($file);
                // Extract exif information from fille
                $this->exif =  $img->exif();
            }
            // store image to directory
            $img->save(public_path($this->image->src));

            // store image information
            $this->image->height     = $img->height();
            $this->image->width      = $img->width();
            $this->image->mime_type  = $img->mime();
            $this->image->save();


            if($this->exif){
                if($this->exif['SectionsFound'] != ""){

                    // process meta data
                    $this->processMetaData();

                    // store image meta
                    $this->image->meta()->create([
                        'camera' => json_encode($this->camera),
                        'author' => json_encode($this->author),
                        'exif'   => json_encode($this->exifMeta)
                    ]);
                }

            }

            return $this->image->with('meta')->find($this->image->id);

        }catch(\Exception $e){

            return [
                'errors' => 'Processing failed!',
                'message' => $e->getMessage()
            ];

        }
    }


    public function find($id)
    {
        return $this->image->with('meta')->find($id);
    }


    /**
     * return image directory with file name to store image file
     * 
     * @return  images/current-month/unique-id.extension
     */

    public function imagePath($extension)
    {
        return 'images/'.date('Y-m').'/'.uniqid().'.'.$extension;
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