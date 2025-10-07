@extends('layouts.app')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Kategori</h1>
            <p class="text-gray-600 mt-2">Kelola kategori untuk barang hilang dan temuan</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Kategori
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($categories->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Laporan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($categories as $category)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $category->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $category->reports_count }} laporan</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $category->created_at->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.categories.edit', $category) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </a>
                                @if($category->reports_count == 0)
                                    <button type="button" onclick="showCategoryDeleteModal({{ $category->id }})" class="text-red-600 hover:text-red-900">
                                        Hapus
                                    </button>
                                @else
                                    <span class="text-gray-400 cursor-not-allowed" title="Kategori tidak bisa dihapus karena sudah digunakan">Hapus</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($categories->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $categories->links() }}
            </div>
            @endif
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada kategori</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kategori pertama.</p>
                <div class="mt-6">
                    <a href="{{ route('admin.categories.create') }}" class="btn-primary">
                        Tambah Kategori Pertama
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<div id="deleteCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-sm mx-4 shadow-xl">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Konfirmasi Hapus</h3>
        <p class="text-sm text-gray-500 mb-6">Apakah Anda yakin ingin menghapus kategori ini?</p>
        <div class="flex justify-end space-x-3">
            <button onclick="closeDeleteModal()" type="button" class="btn-secondary">
                Batal
            </button>
            <form id="deleteCategoryForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function showCategoryDeleteModal(categoryId) {
    const form = document.getElementById('deleteCategoryForm');
    form.action = `/admin/categories/${categoryId}`;
    
    const modal = document.getElementById('deleteCategoryModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    // Cari semua modal yang mungkin terbuka dan sembunyikan
    document.querySelectorAll('[id*="Modal"]').forEach(modal => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });
}
</script>
@endsection