<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catalogue;

class CatalogueController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'image' => 'required|file|mimetypes:image/jpeg,image/png,image/gif',
        ]);
        // $data = $request->all();
        $catalogue = new Catalogue;
        $catalogue->name = $request->name;
        $catalogue->price = $request->price;

        $image = $request->file('image');
        $imageName = time().'.'.$image->extension();
        $image->move(public_path('images'), $imageName);
        $catalogue->image = $imageName;
        // $catalogue->image = $data['image'];
        $catalogue->save();

        return response()->json([
            'success' => true,
            'message' => 'Data saved successfully!',
        ]); 
    }
}
