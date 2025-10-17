<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        // Jika ada filter tanggal dari request, pakai itu. Kalau tidak, pakai hari ini.
        $tanggal = $request->input('tanggal', now()->toDateString());

        $absensi = Absensi::with('siswa')
            ->whereDate('tanggal', $tanggal)
            ->get();

        return view('absensi.index', compact('absensi', 'tanggal'));
    }

    public function scanPage()
    {
        return view('scan');
    }

    public function scanSubmit(Request $request)
    {
        $token = $request->input('siswa_id'); // hasil scan
        $siswa = Siswa::where('qr_token', $token)->first();

        if (!$siswa) {
            return response()->json(['status' => 'error', 'message' => 'Siswa tidak ditemukan'], 404);
        }

        $tanggal = Carbon::now()->format('Y-m-d');

        $absenMasuk = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('tanggal', $tanggal)
            ->where('status', 'masuk')
            ->first();

        if (!$absenMasuk) {
            Absensi::create([
                'siswa_id' => $siswa->id,
                'tanggal' => Carbon::now(),
                'waktu' => Carbon::now()->format('H:i:s'),
                'status' => 'masuk',
            ]);

            return response()->json(['status' => 'success', 'message' => 'Absen masuk dicatat']);
        }

        Absensi::create([
            'siswa_id' => $siswa->id,
            'tanggal' => Carbon::now(),
            'waktu' => Carbon::now()->format('H:i:s'),
            'status' => 'pulang',
        ]);

        // return response()->json(['status' => 'success', 'message' => 'Absen pulang dicatat']);   
        return response()->json([
            'debug_received' => $id,
            'all_ids' => Siswa::pluck('id')
        ]);
    }
}
