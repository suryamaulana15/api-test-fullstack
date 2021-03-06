<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Varian;
use App\Models\Diskon;
use DB;

class ProdukController extends Controller
{
    public function index()
    {
      $produk = Produk::paginate(10);
      return $produk;
    }

    public function show($id)
    {
      $produk = Produk::with(
        [
          'varian' => function($varian){
            $varian->with(['diskon']);
          }
        ])->find($id);
      return $produk;
    }

    public function update(Request $request, Produk $produk)
    {
      $validated = $this->validate($request, [
        'nama' => 'sometimes',
        'deskripsi' => 'sometimes',
        'foto' => 'sometimes',
      ]);

      $produk->update($validated);

      return response()->json([
        'message' => 'berhasil ubah produk'
      ],201);
    }

    public function store(Request $request)
    {
      $validated = $this->validate($request, [
        'nama' => 'required',
        'deskripsi' => 'sometimes',
        'foto' => 'sometimes',
        'varian' => 'required|array|min:1',
        'varian.*.foto' => 'required',
      ]);
      
      $create = DB::transaction(function () use ($request, $validated) {
        $produk = Produk::create($validated);

        foreach($request->varian as $data){
          $varian = $produk->varian()->create([
            "nama" => $data['nama'],
            "harga" => $data['harga'],
            "ukuran" => $data['ukuran']
          ]);

          if ($data['foto']) {
            $media = $varian
            ->addMediaFromBase64($data['foto'])
            ->usingFileName(date('YmdHis') . '.' . 'jpg')->toMediaCollection('foto_varian');
  
            $varian->update(['foto' => $media->getFullUrl() ]);
          }
          
          $diskon = $varian->diskon()->create([
            "persentasi" => $data['diskon']['persentasi']
          ]);
        }
      });
      
      return response()->json([
        'message' => 'sukses input data'
      ],201);
    }

    public function destroy(Produk $produk)
    {
      $produk->delete();
      return response()->json([
        'message' => 'berhasil hapus produk'
      ],201);
    }

    public function updateVarian(Varian $varian, Request $request)
    {
      $validated = $this->validate($request, [
        'nama' => 'sometimes',
        'deskripsi' => 'sometimes',
        'foto' => 'sometimes',
        'harga' => 'sometimes',
        'diskon' => 'sometimes|array',
        'ukuran' => 'sometimes',
      ]);
      
      $update = DB::transaction(function () use ($validated, $varian, $request) {
        $varian->update($validated);

        if ($request->foto) {
          $media = $varian
          ->addMedia($request->foto)
          ->usingFileName(date('YmdHis') . '.' . 'jpg')->toMediaCollection('foto_varian');

          $varian->update(['foto' => $media->getFullUrl() ]);
        }

        $varian->diskon()->update([
          "persentasi" => $validated['diskon']['persentasi']
        ]);
      });

      return response()->json([
        'message' => 'berhasil ubah varian'
      ],201);
    }

    public function tambahVarian(Produk $produk, Request $request)
    {
      $validated = $this->validate($request, [
        'nama' => 'required',
        'deskripsi' => 'sometimes',
        'foto' => 'sometimes',
        'harga' => 'required',
        'diskon' => 'required|array',
        'ukuran' => 'required',
      ]);
      
      $update = DB::transaction(function () use ($validated, $produk, $request) {
        $varian = $produk->varian()->create([
          "nama" => $validated['nama'],
          "harga" => $validated['harga'],
          "ukuran" => $validated['ukuran'],
        ]);
        $diskon = $varian->diskon()->create([
          "persentasi" => $validated['diskon']['persentasi']
        ]);

        if ($request->foto) {
          $media = $varian
          ->addMedia($request->foto)
          ->usingFileName(date('YmdHis') . '.' . 'jpg')->toMediaCollection('foto_varian');

          $varian->update(['foto' => $media->getFullUrl() ]);
        }
      });

      return response()->json([
        'message' => 'berhasil tambah varian'
      ],201);
    }

    public function hapusVarian(Varian $varian)
    {
      $varian->delete();
      return response()->json([
        'message' => 'berhasil hapus varian'
      ],201);
    }
}
