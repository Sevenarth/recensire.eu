<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryFormRequest;
use Illuminate\Http\Request;
use App\Category;

class CategoriesController extends Controller
{
    public function index(Request $request) {
      return view('panel/categories/home', ['cats' => Category::tree()]);
    }

    public function create(Request $request) {
      return view('panel/categories/form', ['cats' => Category::tree(), 'cat' => new Category]);
    }

    public function edit(Request $request, Category $cat) {
      return view('panel/categories/form', ['cats' => Category::tree(), 'cat' => $cat]);
    }

    public function put(CategoryFormRequest $request) {
      $cat = Category::create($request->only([
        'title', 'description'
      ]));
      $cat->parent_id = empty($request->input('parent_id')) ? null : $request->input('parent_id');
      $cat->save();

      return redirect()
        ->route('panel.categories.home')
        ->with('status', 'Categoria aggiunta con successo!');
    }

    public function update(CategoryFormRequest $request, Category $cat) {
      $cat->fill($request->only([
        'title', 'description'
      ]));
      $cat->parent_id = empty($request->input('parent_id')) ? null : $request->input('parent_id');
      $cat->save();

      return redirect()
        ->route('panel.categories.home')
        ->with('status', 'Categoria aggiornata con successo!');
    }

    public function delete(Request $request, Category $cat) {
      try {
        $cat->products()->detach();
        $cat->delete();
      } catch(Illuminate\Database\QueryException $e) {
        return redirect()
          ->route('panel.categories.home')
          ->with('error', 'Questa categoria dipende da altri elementi. Impossibile continuare.');
      }

      return redirect()
        ->route('panel.categories.home')
        ->with('status', 'Categoria rimossa con successo!');
    }
}
