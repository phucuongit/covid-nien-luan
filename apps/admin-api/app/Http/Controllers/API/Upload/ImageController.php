<?php

namespace App\Http\Controllers\API\Upload;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\ImageResource as ImageResource;
use App\Http\Controllers\API\BaseController;
use App\Models\Image;
use Validator;
use Exception;

class ImageController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                "images" => "required|array|max:10",
                "images.*" => "image|mimes:jpeg,jpg,png,gif|max:4096",
                "imageable_id" => "required|numeric|min:0",
                "imageable_type" => [
                    'required',
                    'string',
                    Rule::in(['user', 'result_test', 'vaccination']),
                ]
            ]);
            if($validator->fails())
                return $this->sendError('Validation Error.', $validator->errors(), 404);       
            $imgResponse = [];
            foreach($request->file('images') as $image)
            {
                if ($image->isValid()) {
                    $name = $image->hashName();
                    $path = 
                        Storage::disk('public')->putFile('images', $image);
                    $url = Storage::url($path);
                    //Insert to DB
                    $image = [
                        'name' => $name,
                        'url' => $url,
                        'imageable_id' => $request->input('imageable_id'),
                        'imageable_type' => $request->input('imageable_type'),
                        'type' => $request->input('type')
                    ];
                    $imgResponse[] = new ImageResource(Image::create($image));
                } 
            }
            return $this->sendResponse($imgResponse, 'Successfully. Your domain/url to access image');
        }
        catch (Exception $e) {
            return $this->sendError('Something went wrong', [$e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy($params)
    {
        try{
            $ids = explode(",", $params);
            // Delete files
            $imageNames = Image::findOrFail($ids, 'name');
            foreach ($imageNames as $index => $row)
            {
                Storage::disk('public')->delete('images/'.$row['name']);
            }
            // Delete in DB
            $imageResult = Image::destroy($ids);
            return $this->sendResponse([], "Successfully");
         }
         catch (Exception $e) {
             return $this->sendError('Something went wrong', [$e->getMessage()]);
         }
    }
}
