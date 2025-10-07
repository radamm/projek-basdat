{{-- resources/views/reports/_public_reports_list.blade.php --}}
{{-- VERSI TABEL INTERAKTIF --}}

<section id="temuan" class="min-h-screen bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold mb-6 text-center">Daftar Laporan Terverifikasi</h2>

        <!-- Form Filter (Tetap Sama) -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
            <form method="GET" action="" class="flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama barang..." class="form-input w-full">
                </div>
                <div>
                    <select name="type" class="form-select">
                        <option value="">Semua Tipe</option>
                        <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Barang Temuan</option>
                        <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Barang Hilang</option>
                    </select>
                </div>
                <div>
                    <select name="category" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <select name="building" class="form-select">
                        <option value="">Semua Gedung</option>
                        @foreach($buildings as $building)
                            <option value="{{ $building->id }}" {{ request('building') == $building->id ? 'selected' : '' }}>{{ $building->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn-primary">Cari</button>
                <a href="{{ url()->current() }}" class="btn-outline">Reset</a>
            </form>
        </div>

        <!-- Tampilan Tabel -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            {{-- Wrapper ini membuat tabel bisa di-scroll horizontal di layar kecil --}}
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($reports as $report)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if($report->photo)
                                                <img class="h-12 w-12 rounded-lg object-cover" src="{{ Storage::url($report->photo) }}" alt="Foto {{ $report->item_name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $report->item_name }}</div>
                                            <div class="text-sm text-gray-500">Oleh: {{ optional($report->user)->name ?? 'Anonim' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $report->type === 'lost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $report->type === 'lost' ? 'Barang Hilang' : 'Barang Temuan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ optional($report->category)->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ optional($report->room)->name ?? 'Lokasi tidak spesifik' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $report->event_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    @guest
                                        <a href="{{ route('login') }}" class="btn-primary-sm">Login untuk Klaim</a>
                                    @endguest

                                    @auth
                                        {{-- Jika yang login adalah Petugas atau Admin --}}
                                        @if(in_array(Auth::user()->role, ['petugas', 'admin']))
                                            
                                            {{-- PERBAIKAN DI SINI: Arahkan ke route khusus admin/petugas --}}
                                            <a href="{{ route('petugas.reports.show', $report) }}" class="btn-secondary-sm">Detail</a>
                                            <a href="{{ route('petugas.reports.edit', $report) }}" class="btn-warning-sm">Edit</a>
                                            
                                            <form action="{{ Auth::user()->role === 'admin' ? route('admin.reports.destroy', $report) : route('petugas.reports.destroy', $report) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin hapus?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn-danger-sm">Hapus</button>
                                            </form>
                                        
                                        {{-- Jika yang login adalah Pengguna Biasa --}}
                                        @elseif(Auth::user()->role === 'pengguna')
                                            <a href="{{ route('reports.show', $report) }}" class="btn-secondary-sm">Detail</a>
                                            @if($report->type === 'found' && $report->user_id !== Auth::id())
                                                <a href="{{ route('reports.show', $report) }}" class="btn-primary-sm">Klaim</a>
                                            @endif
                                        @endif
                                    @endauth
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-12 px-6">
                                    <p class="text-gray-500">Tidak ada laporan terverifikasi yang cocok dengan filter Anda.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
             @if ($reports->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $reports->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>
</section>
<!-- ============================================ -->
<!-- == INILAH HTML UNTUK MODAL KLAIM (BARU) == -->
<!-- ============================================ -->
<div id="claimModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="modalReportName" class="text-xl font-bold text-gray-800">Klaim Barang</h3>
            <button id="closeModalButton" class="text-gray-500 hover:text-gray-800">&times;</button>
        </div>
        <!-- Modal Body -->
        <div class="p-6">
            <form id="claimForm" method="POST" action=""> {{-- Action akan diisi oleh JavaScript --}}
                @csrf
                <div>
                    <label for="modalDescription" class="block text-sm font-semibold text-gray-800 mb-2">
                        Bukti Kepemilikan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="modalDescription" rows="5" 
                              class="w-full px-3 py-2 text-gray-700 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors"
                              placeholder="Jelaskan bukti bahwa barang ini milik Anda (ciri-ciri khusus, nomor seri, waktu kehilangan, dll.)." 
                              required></textarea>
                    <p class="mt-2 text-xs text-gray-500">Minimal 20 karakter.</p>
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="btn-primary">Ajukan Klaim</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- == INILAH JAVASCRIPT UNTUK MODAL (BARU) == -->
<!-- ============================================ -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const claimModal = document.getElementById('claimModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const claimForm = document.getElementById('claimForm');
    const modalReportName = document.getElementById('modalReportName');
    const modalDescription = document.getElementById('modalDescription');
    const claimButtons = document.querySelectorAll('.claim-button');

    // Fungsi untuk menutup modal
    function closeModal() {
        claimModal.classList.add('hidden');
    }

    // Event listener untuk tombol 'x'
    closeModalButton.addEventListener('click', closeModal);
    // Event listener untuk klik di luar area modal
    claimModal.addEventListener('click', function(event) {
        if (event.target === claimModal) {
            closeModal();
        }
    });

    // Loop melalui setiap tombol 'Klaim' di tabel
    claimButtons.forEach(button => {
        button.addEventListener('click', function (event) {
            const reportId = event.currentTarget.dataset.reportId;
            const reportName = event.currentTarget.dataset.reportName;
            
            // Atur judul modal
            modalReportName.textContent = 'Klaim Barang: ' + reportName;
            
            // Atur action form agar mengarah ke URL yang benar
            claimForm.action = '/reports/' + reportId + '/claims';

            // Reset textarea
            modalDescription.value = '';

            // Tampilkan modal
            claimModal.classList.remove('hidden');
        });
    });
});
</script>