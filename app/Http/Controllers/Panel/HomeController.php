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
      return "<script>
      var fieldId = window.opener.document.getElementById('upload-image').dataset.target;
      window.opener.document.getElementById(fieldId).value = '{$url}'
      window.opener.updateProfileImage()
      window.close()
      </script>";
    }
}
