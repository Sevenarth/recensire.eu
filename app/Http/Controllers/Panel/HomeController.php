<?php

namespace App\Http\Controllers\Panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageUploadRequest;
use Storage;

class HomeController extends Controller
{
    public function index(Request $request) {

      return view("panel/home");
    }

    public function upload(Request $request) {

      return view("panel/upload");
    }

    public function postUpload(ImageUploadRequest $request) {
      $url = Storage::disk('public')->url($request->image->store('images', 'public'));
      if($fn = $request->input('fn')) {
        $fn = $request->input('fn') . "('{$url}')";
      } else if($iid = $request->input('field')) {
        $id = $iid . "-field";
        $fn = "updateImageField('".$iid."')";
      } else {
        $id = "profile_image";
        $fn = "updateImage()";
      }

      return "<script>".
      (!empty($id) ? "window.opener.document.getElementById('{$id}').value = '{$url}'" : "")
      ."window.opener.{$fn}
      window.close()
      </script>";
    }
}
