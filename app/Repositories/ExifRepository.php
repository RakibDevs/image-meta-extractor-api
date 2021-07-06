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
        3 => 'cm',
    ];


    /**
     * Image YCbCr Positioning Library
     * Source: https://exiftool.org/TagNames/EXIF.html
     */

    protected $YCbCr = [
        1 => 'Centered',
        2 => 'Co-sited',
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
        9 => 'Bulb',
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


    public function processMetaData()
    {
        $this->exif = (object) ($this->encodeExifToUtf8($this->exif));

        if ($this->exif) {
            $this->extractCameraInformation();
            $this->extractAuthorInformation();
            $this->extractExifInformation();
        }
    }

    protected function extractCameraInformation()
    {
        // set camera
        if (isset($this->exif->Make)) {
            $this->camera['Make'] = $this->exif->Make;
        }
        if (isset($this->exif->Model)) {
            $this->camera['Model'] = $this->exif->Model;
        }
        if (isset($this->exif->ExposureTime)) {
            $this->camera['Exposure'] = $this->exif->ExposureTime;
        }
        if (isset($this->exif->ApertureValue)) {
            $this->camera['Aperture'] = $this->exif->ApertureValue;
        }
        if (isset($this->exif->FocalLength)) {
            $this->camera['Focal Length'] = $this->exif->FocalLength;
        }
        if (isset($this->exif->Make)) {
            $this->camera['Focal Length (35mm)'] = $this->exif->FocalLengthIn35mmFilm;
        }
        if (isset($this->exif->Make)) {
            $this->camera['ISO Speed'] = $this->exif->ISOSpeedRatings;
        }
        if (isset($this->exif->Make)) {
            $this->camera['Shutter Speed'] = $this->exif->ShutterSpeedValue;
        }
        if (isset($this->exif->Make)) {
            $this->camera['Make'] = $this->exif->Make;
        }
        
        if (isset($this->exif->Flash)) {
            $this->camera['Flash'] = $this->exif->Flash == 1?'Flash':'No Flash';
        }
    }

    protected function extractAuthorInformation()
    {
        $this->author = [
            'Author' => $this->exif->Author ?? '',
            'Copyright' => $this->exif->Copyright ?? '',
        ];
    }

    protected function extractExifInformation()
    {
        if (isset($this->exif->ImageUniqueID)) {
            $this->exifMeta['Image Unique ID'] = $this->exif->ImageUniqueID;
        }
        if (isset($this->exif->Orientation)) {
            $this->exifMeta['Orientation'] = $this->getOrientation($this->exif->Orientation);
        }
        if (isset($this->exif->XResolution)) {
            $this->exifMeta['Horizontal Resolution'] = $this->exif->XResolution;
        }
        if (isset($this->exif->YResolution)) {
            $this->exifMeta['Vertical Resolution'] = $this->exif->YResolution;
        }
        if (isset($this->exif->ResolutionUnit)) {
            $this->exifMeta['Resolution Unit'] = $this->getResoulutionUnit($this->exif->ResolutionUnit);
        }
        if (isset($this->exif->Software)) {
            $this->exifMeta['Software'] = $this->exif->Software;
        }
        if (isset($this->exif->DateTime)) {
            $this->exifMeta['Modify Date'] = $this->exif->DateTime;
        }
        if (isset($this->exif->YCbCrPositioning)) {
            $this->exifMeta['YCbCr Positioning'] = $this->getYCbCrPositioning($this->exif->YCbCrPositioning);
        }
        if (isset($this->exif->FNumber)) {
            $this->exifMeta['FNumber'] = $this->exif->FNumber;
        }
        if (isset($this->exif->ExposureProgram)) {
            $this->exifMeta['Exposure Program'] = $this->getExposureProgram($this->exif->ExposureProgram);
        }
        if (isset($this->exif->DateTimeOriginal)) {
            $this->exifMeta['Original Date'] = $this->exif->DateTimeOriginal;
        }
        if (isset($this->exif->DateTimeDigitized)) {
            $this->exifMeta['Created Date'] = $this->exif->DateTimeDigitized;
        }
        if (isset($this->exif->MeteringMode)) {
            $this->exifMeta['Metering Mode'] = $this->getMeteringMode($this->exif->MeteringMode);
        }

        // calculate latitude
        if (isset($this->exif->GPSLatitude)) {
            $this->exifMeta['Latitude'] = $this->dmsToDegree($this->exif->GPSLatitude);
        }
        // calculate longitude
        if (isset($this->exif->GPSLongitude)) {
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
        return round((int) $arr[0] + ((((int) $arr[1] * 60) + ((int) $arr[2])) / 3600), 2);
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
            foreach ($dat as $i => $d) {
                $ret[ $i ] = self::encodeExifToUtf8($d);
            }

            return $ret;
        } elseif (is_object($dat)) {
            foreach ($dat as $i => $d) {
                $dat->$i = self::encodeExifToUtf8($d);
            }

            return $dat;
        } else {
            return $dat;
        }
    }
}
