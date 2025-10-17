<x-app-layout>
<div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Data Siswa</h1>
        <button id="btn-open-create" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Siswa</button>
    </div>

    @if(session('success'))
    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">#</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">NIS</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nama</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Kelas</th>
                    <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">QR</th>
                    <th class="px-4 py-2 text-right text-sm font-medium text-gray-600">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php $i=1; @endphp
                @foreach($siswas as $siswa)
                <tr>
                    <td class="px-4 py-2 text-sm">{{ $i++ }}</td>
                    <td class="px-4 py-2 text-sm">{{ $siswa->nis }}</td>
                    <td class="px-4 py-2 text-sm">{{ $siswa->nama }}</td>
                    <td class="px-4 py-2 text-sm">{{ $siswa->kelas }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($siswa->qr_code_path)
                            <a href="{{ asset('storage/'.$siswa->qr_code_path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$siswa->qr_code_path) }}" alt="QR" class="w-20 h-20 object-contain">
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">Belum</span>
                        @endif
                    </td>
                    <td class="px-4 py-2 text-right text-sm">
                        <button onclick="openEditModal({{ $siswa->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded mr-2">Edit</button>

                        <form action="{{ route('siswa.destroy', $siswa->id) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin ingin menghapus siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Create Modal -->
<div id="modal-create" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg w-96 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold">Tambah Siswa</h3>
            <button onclick="closeCreateModal()" class="text-gray-500">✕</button>
        </div>
        <form action="{{ route('siswa.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-sm">NIS</label>
                <input name="nis" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-3">
                <label class="block text-sm">Nama</label>
                <input name="nama" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-3">
                <label class="block text-sm">Kelas</label>
                <input name="kelas" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div id="modal-edit" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg w-96 p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-semibold">Edit Siswa</h3>
            <button onclick="closeEditModal()" class="text-gray-500">✕</button>
        </div>
        <form id="form-edit" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="block text-sm">NIS</label>
                <input id="edit-nis" name="nis" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-3">
                <label class="block text-sm">Nama</label>
                <input id="edit-nama" name="nama" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="mb-3">
                <label class="block text-sm">Kelas</label>
                <input id="edit-kelas" name="kelas" required class="w-full border rounded px-3 py-2" />
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 rounded border">Batal</button>
                <button type="submit" class="px-4 py-2 rounded bg-yellow-500 text-white">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Create modal
    const modalCreate = document.getElementById('modal-create');
    document.getElementById('btn-open-create').addEventListener('click', () => {
        modalCreate.classList.remove('hidden');
        modalCreate.classList.add('flex');
    });
    function closeCreateModal() {
        modalCreate.classList.add('hidden');
        modalCreate.classList.remove('flex');
    }

    // Edit modal (fetch data via AJAX and fill)
    const modalEdit = document.getElementById('modal-edit');
    async function openEditModal(id) {
        const res = await fetch(`/siswa/${id}/edit`);
        const data = await res.json();
        document.getElementById('edit-nis').value = data.nis;
        document.getElementById('edit-nama').value = data.nama;
        document.getElementById('edit-kelas').value = data.kelas;
        document.getElementById('form-edit').action = `/siswa/${id}`;
        modalEdit.classList.remove('hidden');
        modalEdit.classList.add('flex');
    }
    function closeEditModal() {
        modalEdit.classList.add('hidden');
        modalEdit.classList.remove('flex');
    }
</script>

</x-app-layout>