<?php

namespace App\Http\Controllers\IDPrintingController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FileController extends \App\Http\Controllers\Controller
{

    public function loadimages()
    {
        // Get the full path to the storage directory
        $basePath = base_path();
        $parentDirectory = dirname($basePath, 1);
        $currentDomain = parse_url(\URL::to('/'), PHP_URL_HOST);
        $storagePath = $parentDirectory . '/' . $currentDomain . '/storage/TEMPLATES';
        $storagePath2 = $parentDirectory . '/' . $currentDomain . '/storage/IMAGES';

        // Get all image filenames from the "TEMPLATES" directory
        $imageFiles = File::files($storagePath);
        $imageFiles2 = File::files($storagePath2);

        $imageNames = [];
        $imageNames2 = [];
        foreach ($imageFiles as $imageFile) {
            $imageNames[] = asset('/storage/TEMPLATES') . '/' . $imageFile->getFilename();
        }

        foreach ($imageFiles2 as $imageFile) {
            $imageNames2[] = asset('/storage/IMAGES') . '/' . $imageFile->getFilename();
        }

        return [
            (object) [
                'data' => $imageNames,
                'assets' => $imageNames2,
            ]
        ];
    }


    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Generate a unique filename based on timestamp
        $imageName = time() . '.' . $request->image->getClientOriginalExtension();

        // Get the current domain
        $currentDomain = parse_url(\URL::to('/'), PHP_URL_HOST);

        // Get the base path and parent directory
        $basePath = base_path();
        $parentDirectory = dirname($basePath, 1);
        $storagePath = '';
        if ($request->purpose === 'bgtemplate') {

            // Construct the full path to the storage directory
            $storagePath = $parentDirectory . '/' . $currentDomain . '/storage/TEMPLATES';
        } else {
            // Construct the full path to the storage directory
            $storagePath = $parentDirectory . '/' . $currentDomain . '/storage/IMAGES';
        }

        // Decode base64 data and save the image
        $base64Data = base64_encode(file_get_contents($request->image->getRealPath()));
        $decodedData = base64_decode($base64Data);
        $clouddestinationPath = $storagePath . '/' . $imageName;
        file_put_contents($clouddestinationPath, $decodedData);

        return [
            (object) [
                'status' => 200,
                'url' => $currentDomain,
                'fullurl' => $storagePath,
                'parent' => $parentDirectory,
                'statusCode' => "success",
                'message' => "File Added Successfully!",
                'uploadedFilePath' => $clouddestinationPath, // Include the uploaded file path in the response
            ]
        ];
    }




}
