<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ImageRepository;
use Illuminate\Http\Request;
use Image;

class ImageController extends Controller
{

    public $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        $exif = ($this->imageRepository->store('https://www.exiv2.org/include/img_1771.jpg','url'));
        dd($exif);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $exif = ($this->imageRepository->store($request->file('image')));

        return response()->json($exif);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function url(Request $request)
    {
        $exif = ($this->imageRepository->store($request->url,'url'));

        return response()->json($exif);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

  
}
