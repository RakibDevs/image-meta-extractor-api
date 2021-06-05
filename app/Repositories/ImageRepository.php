<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Storage;
use Image;

class ImageRepository
{
    public function __constuct()
    {
        
    }

    public static function convert_from_latin1_to_utf8_recursively($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::convert_from_latin1_to_utf8_recursively($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::convert_from_latin1_to_utf8_recursively($d);

            return $dat;
        } else {
            return $dat;
        }
    }

    public function store($file)
    {

        $directory   = $this->imagePath($file->getClientOriginalExtension());
        $image  = Image::make($file);
        Storage::put($directory, $image->encode());
        //$exif = $image->exif();
        $exif = $image->exif();

        $exif = $this->convert_from_latin1_to_utf8_recursively($exif);

        return $exif;

    }

    public function storeViaUrl($url)
    {
        // get directory with file name from url
        $directory   = $this->imagePath($this->getExtension($url));
        $image  = Image::make($url);
        Storage::put($directory, $image->encode());
        // Extract exif information from url
        $exif = exif_read_data($url);
        $exif = $this->convert_from_latin1_to_utf8_recursively($exif);

        return $exif;     
    }


    public function imagePath($extension)
    {
        return 'images/'.date('Y-m').'/'.uniqid().'.'.$extension;
    }

    public function getExtension($url)
    {
        return last(explode(".",basename($url)));
    }
}