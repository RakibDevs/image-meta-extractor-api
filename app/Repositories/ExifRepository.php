<?php

namespace App\Repositories;

class ExifRepository 
{
	/**
     * Extracted exif value of an image
     */

    public $exif;

    /**
     * Author and copyright information on an image
     */

    public $author = [];

    /**
     * Camera information of an image
     */

    public $camera = [];

    /**
     * exif meta information of an image
     */

    public $exifMeta = [];

    /**
     * Image orientation Library
     * Source: https://exiftool.org/TagNames/EXIF.html 
     */

    protected $orientation = [
    	1 => 'Normal',
		2 => 'Mirror Horizontal',
		3 => 'Rotate 180',
		4 => 'Mirror Vertical',
		5 => 'Mirror Horizontal and Rotate 270 CW',
		6 => 'Rotate 90 CW',
		7 => 'Mirror Horizontal and Rotate 90 CW',
		8 => 'Rotate 270 CW',
    ];

    /**
     * Image resolution unit Library
     * Source: https://exiftool.org/TagNames/EXIF.html 
     */

    protected $resolutionUnit = [
    	1 => '',
		2 => 'inches',
		3 => 'cm'
    ];


    /**
     * Image YCbCr Positioning Library
     * Source: https://exiftool.org/TagNames/EXIF.html 
     */

    protected $YCbCr = [
    	1 => 'Centered',
		2 => 'Co-sited'
    ];


    /**
     * Image Exposure Program Library
     * Source: https://exiftool.org/TagNames/EXIF.html 
     */

    protected $exposureProgram = [
    	0 => 'Not Defined',
		1 => 'Manual',
		2 => 'Program AE',
		3 => 'Aperture-priority AE',
		4 => 'Shutter speed priority AE',
		5 => 'Creative (Slow speed)',
		6 => 'Action (High speed)',
		7 => 'Portrait',
		8 => 'Landscape',
		9 => 'Bulb'
    ];


    /**
     * Image Metering Mode Library
     * Source: https://exiftool.org/TagNames/EXIF.html 
     */

    protected $meteringMode = [
    	0 => 'Unknown',
		1 => 'Average',
		2 => 'Center-weighted average',
		3 => 'Spot',
		4 => 'Multi-spot',
		5 => 'Multi-segment',
		6 => 'Partial',
		255 => 'Other',
    ];


    public function extractInformation()
    {
    	// set camera 
        $this->camera = [
        	'Make' 			=> $this->exif->Make??'',
        	'Model' 		=> $this->exif->Model??'',
        	'Exposure' 		=> $this->exif->ExposureTime??'',
        	'Aperture' 		=> $this->exif->ApertureValue??'',
        	'Focal Length' 	=> $this->exif->FocalLength??'',
        	'Focal Length (35mm)' 	=> $this->exif->FocalLengthIn35mmFilm??'',
        	'ISO Speed' 	=> $this->exif->ISOSpeedRatings??'',
        	'Shutter Speed' => $this->exif->ShutterSpeedValue??''
        ];
        if(isset($this->exif->GPSLatitude)){
    		$this->camera['Flash'] 	= $this->exif->Flash == 1?'Flash':'No Flash';
    	}

    
        $this->author = [
        	'Author' => $this->exif->Author??'',
        	'Copyright' => $this->exif->Copyright??''
        ];
    
    	
        $this->exifMeta = [
        	'Image Unique ID' 	    => $this->exif->ImageUniqueID??'',
        	'Orientation' 			=> $this->getOrientation($this->exif->Orientation??null),
        	'Horizontal Resolution' => $this->exif->XResolution??'' ,
        	'Vertical Resolution' 	=> $this->exif->YResolution??'',
        	'Resolution Unit' 		=> $this->getResoulutionUnit($this->exif->ResolutionUnit??null),
        	'Software' 				=> $this->exif->Software??'',
        	'Modify Date' 			=> $this->exif->DateTime??'',
        	'YCbCr Positioning'     => $this->getYCbCrPositioning($this->exif->YCbCrPositioning??null),
        	'FNumber' 				=> $this->exif->FNumber??'',
        	'Exposure Program' 		=> $this->getExposureProgram($this->exif->ExposureProgram??null),
        	'Original Date' 		=> $this->exif->DateTimeOriginal??'',
        	'Created Date' 			=> $this->exif->DateTimeDigitized??'',
        	'Brightness' 			=> $this->exif->BrightnessValue??'',
        	'Metering Mode' 		=> $this->getMeteringMode($this->exif->MeteringMode??null)
        ];
        // calculate latitude
        if(isset($this->exif->GPSLatitude)){
        	$this->exifMeta['Latitude']  = $this->dmsToDegree($this->exif->GPSLatitude);
        }
        // calculate longitude
        if(isset($this->exif->GPSLongitude)){
        	$this->exifMeta['Longitude'] = $this->dmsToDegree($this->exif->GPSLongitude);
        }

    }

    protected function getOrientation($value)
    {
    	return (isset($this->orientation[$value])?$this->orientation[$value]:'');
    }

    protected function getResoulutionUnit($value)
    {
    	return (isset($this->resolutionUnit[$value])?$this->resolutionUnit[$value]:'');
    }

    protected function getYCbCrPositioning($value)
    {
    	return (isset($this->YCbCr[$value])?$this->YCbCr[$value]:'');
    }

    protected function getExposureProgram($value)
    {
    	return (isset($this->exposureProgram[$value])?$this->exposureProgram[$value]:'');
    }


    protected function getMeteringMode($value)
    {
    	return (isset($this->exposureProgram[$value])?$this->exposureProgram[$value]:'');
    }

    /**
     * Converting DMS ( Degrees / minutes / seconds ) to decimal format
     * 
     * @return  string | degree   
     */
    protected function dmsToDegree($arr)
	{
	    return (int) $arr[0]+((((int) $arr[1]*60)+((int) $arr[2]))/3600);
	}


    


    /**
     * to avoid Malformed utf-8 characters, 
     * 
     * @return  array encoded  
     */

    public static function encodeExifToUtf8($dat)
    {
        if (is_string($dat)) {
            return utf8_encode($dat);
        } elseif (is_array($dat)) {
            $ret = [];
            foreach ($dat as $i => $d) $ret[ $i ] = self::encodeExifToUtf8($d);

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) $dat->$i = self::encodeExifToUtf8($d);

            return $dat;
        } else {
            return $dat;
        }
    }
}