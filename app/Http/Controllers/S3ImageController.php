<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class S3ImageController extends Controller
{


    /**
    * Create view file
    *
    * @return void
    */
    public function imageUpload()
    {//dd(config('filesystems.disks.s3.region'));
      $directories = Storage::disk('s3')->directories();
      $S3AllFiles = Storage::disk('s3')->allFiles();
      $files = array();
      foreach ($S3AllFiles as $key => $file) {
        $files[] = Storage::disk('s3')->url($file);
      }
	  // dd($S3AllFiles);
      $currentUrl = "";
    	return view('image-upload',compact('files','S3AllFiles','directories','currentUrl'));
    }


    /**
    * Manage Post Request
    *
    * @return void
    */
    public function imageUploadPost(Request $request)
    {   $data = $request->all();
    	  $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $directory = time().'.'.$request->image->getClientOriginalExtension();
        if (!empty($data['directory'])) {
          $directory = $data['directory'].'/'.$directory;
        }
        $image = $request->file('image');
        $t = Storage::disk('s3')->put($directory, file_get_contents($image), 'public');

        $imageName = Storage::disk('s3')->url($directory);


    	return back()
    		->with('success','Image Uploaded successfully.')
    		->with('path',$imageName);
    }

    public function deleteFile(Request $request)
    {
      $data = $request->all();
      $test = Storage::disk('s3')->delete($data['prefix']);
      return back()
    		->with('success','File delete successfully.');
    }
    public function createFolder(Request $request)
    {
      $data = $request->all();
      $directory = $data['folder_name'];
      if (!empty($data['directory'])) {
        $directory = $data['directory'].'/'.$data['folder_name'];
      }
      $test = Storage::disk('s3')->makeDirectory($directory);

      return back()
    		->with('success','Folder create successfully.');
    }
    public function enterDirectory(Request $request)
    {
      $data = $request->all();
      $files = array();
      $S3AllFiles = Storage::disk('s3')->files($data['prefix']);
      foreach ($S3AllFiles as $key => $file) {
        $url = Storage::disk('s3')->url($file);
        $files[] = $url;
      }
      $directories = Storage::disk('s3')->directories($data['prefix']);
      $currentUrl = $data['prefix'];
      return view('image-upload',compact('files','S3AllFiles','directories','currentUrl'));
    }
}
