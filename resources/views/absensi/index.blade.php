<x-app-layout>

<div class="max-w-5xl mx-auto p-6">
    <h2 class="text-2xl font-semibold mb-6">Absensi Hari Ini</h2>

    {{-- Filter Tanggal --}}
    <form method="GET" action="{{ route('absensi.index') }}" class="mb-6 flex items-center gap-3">
        <label for="tanggal" class="text-sm font-medium">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal"
               value="{{ $tanggal }}"
               class="border rounded-lg px-3 py-2 text-sm focus:ring focus:ring-blue-300">
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm">
            Filter
        </button>
    </form>

    {{-- Tabel --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Siswa</th>
                    <th class="px-4 py-3 text-left">Kelas</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Waktu Masuk</th>
                    <th class="px-4 py-3 text-left">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($absensi as $item)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 py-3">{{ $item->siswa->nama }}</td>
                        <td class="px-4 py-3">{{ $item->siswa->kelas }}</td>
                        <td class="px-4 py-3">{{ $item->tanggal }}</td>
                        <td class="px-4 py-3">{{ $item->waktu }}</td>
                        <td class="px-4 py-3">
                            @if ($item->status === 'masuk')
                                <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Masuk</span>
                            @elseif ($item->status === 'pulang')
                                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded-full text-xs">Pulang</span>
                            @else
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded-full text-xs">{{ $item->status }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">
                            Belum ada absensi pada tanggal ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-app-layout>