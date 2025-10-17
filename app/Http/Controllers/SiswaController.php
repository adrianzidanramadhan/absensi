<?php

namespace App\Http\Controllers;

use App\Models\Siswa;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;


class SiswaController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth'); // proteksi, hanya admin yang telah login
    // }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $siswas = Siswa::orderBy('nama')->get();
        return view('siswa.index', compact('siswas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nis' => 'required|unique:siswas,nis',
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        // create siswa (model boot akan mengisi qr_token)
        $siswa = Siswa::create($data);

        // gunakan qr_token
        $token = $siswa->qr_token;
        $relativePath = 'qr/' . $token . '.png'; // token-based filename
        $fullStoragePath = storage_path('app/public/' . $relativePath);

        if (!file_exists(dirname($fullStoragePath))) {
            mkdir(dirname($fullStoragePath), 0755, true);
        }

        // generate QR berisi token
        \QrCode::format('png')->size(300)->generate($token, $fullStoragePath);

        $siswa->qr_code_path = $relativePath;
        $siswa->save();

        return redirect()->route('siswa.index')->with('success','Siswa berhasil ditambahkan.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        return response()->json($siswa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'nis' => ['required', Rule::unique('siswas','nis')->ignore($siswa->id)],
            'nama' => 'required',
            'kelas' => 'required',
        ]);

        $siswa->update($data);

        return redirect()->route('siswa.index')->with('success','Data siswa diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        // hapus file QR jika ada
        if ($siswa->qr_code_path) {
            Storage::delete('public/' . $siswa->qr_code_path);
        }

        $siswa->delete();

        return redirect()->route('siswa.index')->with('success','Siswa dihapus.');
    }
}
