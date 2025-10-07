@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-8">
            <h1 class="text-2xl font-bold text-white">Buat Laporan Temuan (Petugas)</h1>
            <p class="text-blue-100 mt-2">Laporan yang dibuat akan otomatis disetujui.</p>
        </div>

        <form method="POST" action="{{ route('petugas.reports.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            {{-- Petugas hanya bisa membuat laporan 'found', jadi input 'type' disembunyikan --}}
            <input type="hidden" name="type" value="found">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="item_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                    <input type="text" name="item_name" id="item_name" value="{{ old('item_name') }}" 
                           class="form-input" placeholder="Contoh: Kunci Motor Honda">
                    @error('item_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
               <!-- Building -->
                <div>
                    <label for="building_id" class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                    <select name="building_id" id="building_id" class="form-select">
                        <option value="">Pilih Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ old('building_id') == $building->id ? 'selected' : '' }}>
                                {{ $building->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Room -->
                <div>
                    <label for="room_id" class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                    <select name="room_id" id="room_id" class="form-select">
                        <option value="">Pilih Ruangan</option>
                    </select>
                    @error('room_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Event Date -->
                <div>
                    <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Kejadian</label>
                    <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}" 
                           class="form-input" max="{{ date('Y-m-d') }}">
                    @error('event_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Photo -->
                <div>
                    <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">Foto (Opsional)</label>
                    <input type="file" name="photo" id="photo" accept="image/*" class="form-input">
                    <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG. Maksimal 2MB.</p>
                    @error('photo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>
            
             <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Detail</label>
                <textarea name="description" id="description" rows="4" class="form-textarea" 
                          placeholder="Jelaskan detail barang, ciri-ciri khusus, kondisi, dll.">{{ old('description') }}</textarea>
                @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('petugas.reports') }}" class="btn-outline">Batal</a>
                <button type="submit" class="btn-primary">Kirim Laporan</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const buildingSelect = document.getElementById('building_id');
    const roomSelect = document.getElementById('room_id');
    
    // Konversi data dari PHP ke objek JavaScript yang mudah dicari
    const buildingsData = @json($buildings->keyBy('id')); 
    
    function updateRooms() {
        const buildingId = buildingSelect.value;
        const oldRoomId = '{{ old('room_id') }}';
        
        // Kosongkan daftar ruangan setiap kali gedung diganti
        roomSelect.innerHTML = '<option value="">Pilih Ruangan</option>';
        
        // Jika gedung dipilih dan datanya ada
        if (buildingId && buildingsData[buildingId] && buildingsData[buildingId].rooms) {
            // Loop melalui setiap ruangan di dalam gedung yang dipilih
            buildingsData[buildingId].rooms.forEach(room => {
                const option = document.createElement('option');
                option.value = room.id;
                option.textContent = room.name;
                
                // Jika ada data lama (saat validasi gagal), pilih kembali ruangan yang sebelumnya
                if (room.id == oldRoomId) {
                    option.selected = true;
                }
                
                roomSelect.appendChild(option);
            });
        }
    }

    // Pasang event listener: jalankan fungsi updateRooms setiap kali dropdown gedung diubah
    buildingSelect.addEventListener('change', updateRooms);
    
    // PENTING: Panggil fungsi ini sekali saat halaman dimuat
    // Ini untuk menangani kasus jika pengguna kembali ke halaman ini setelah validasi gagal,
    // agar dropdown ruangan tetap terisi sesuai gedung yang sudah dipilih.
    if (buildingSelect.value) {
        updateRooms();
    }
});
</script>
@endsection