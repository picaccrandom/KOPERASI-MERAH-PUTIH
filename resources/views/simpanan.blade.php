@extends('layouts.third')

@section('title', 'Simpanan - Koperasi Merah Putih')

@section('content')
<!-- Header Card -->
<div class="mb-6 bg-white rounded-lg border border-gray-200">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Data Simpanan</h2>
            <p class="text-sm text-gray-600">Kelola data simpanan anggota</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <input type="text" placeholder="Search..." 
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
            </div>
            <button onclick="showAddSimpananModal()" 
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center text-sm">
                <i class="fas fa-plus mr-2"></i>
                Tambah Simpanan
            </button>
        </div>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Show 
            <select class="ml-1 border border-gray-300 rounded px-2 py-1 text-sm">
                <option>10</option>
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
            entries
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="table-gudang">
            <thead>
                <tr>
                    <th class="text-left">NO.</th>
                    <th class="text-left">NAMA ANGGOTA</th>
                    <th class="text-left">KATEGORI</th>
                    <th class="text-left">TANGGAL</th>
                    <th class="text-left">NOMINAL/SETORAN</th>
                    <th class="text-left">ACTION</th>
                </tr>
            </thead>
            <tbody>
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">1</td>
                    <td>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                <span class="text-pink-600 font-bold text-sm">S</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Sindi Nur Amelia</div>
                                <div class="text-xs text-gray-500">081234567890</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                            Wajib
                        </span>
                    </td>
                    <td>23 September 2024</td>
                    <td class="font-bold text-red-600">Rp 350.000</td>
                    <td>
                        <div class="flex space-x-2">
                            <button onclick="detailSimpanan(1)" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editSimpanan(1)" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteSimpanan(1)" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">2</td>
                    <td>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-bold text-sm">P</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Putra Pratama</div>
                                <div class="text-xs text-gray-500">085748278940</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                            Wajib
                        </span>
                    </td>
                    <td>24 September 2024</td>
                    <td class="font-bold text-red-600">Rp 150.000</td>
                    <td>
                        <div class="flex space-x-2">
                            <button onclick="detailSimpanan(2)" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editSimpanan(2)" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteSimpanan(2)" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">3</td>
                    <td>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-bold text-sm">P</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Putra Pratama</div>
                                <div class="text-xs text-gray-500">085748278940</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                            Pokok
                        </span>
                    </td>
                    <td>25 September 2024</td>
                    <td class="font-bold text-red-600">Rp 50.000</td>
                    <td>
                        <div class="flex space-x-2">
                            <button onclick="detailSimpanan(3)" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editSimpanan(3)" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteSimpanan(3)" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                
                <tr class="hover:bg-gray-50">
                    <td class="font-medium">4</td>
                    <td>
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                <span class="text-pink-600 font-bold text-sm">S</span>
                            </div>
                            <div>
                                <div class="font-medium text-gray-900">Sindi Nur Amelia</div>
                                <div class="text-xs text-gray-500">081234567890</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="px-2 py-1 text-xs font-semibold rounded bg-yellow-100 text-yellow-800">
                            Sukarela
                        </span>
                    </td>
                    <td>26 September 2024</td>
                    <td class="font-bold text-red-600">Rp 100.000</td>
                    <td>
                        <div class="flex space-x-2">
                            <button onclick="detailSimpanan(4)" class="text-blue-600 hover:text-blue-900" title="Detail">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editSimpanan(4)" class="text-yellow-600 hover:text-yellow-900" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteSimpanan(4)" class="text-red-600 hover:text-red-900" title="Hapus">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-600">
            Showing 1 to 4 of 4 entries
        </div>
        <div class="flex items-center space-x-2">
            <button class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50" disabled>
                Previous
            </button>
            <button class="px-3 py-1 bg-red-600 text-white rounded text-sm">
                1
            </button>
            <button class="px-3 py-1 border border-gray-300 rounded text-sm">
                Next
            </button>
        </div>
    </div>
</div>

<!-- Keterangan -->
<div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
    <p class="text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>Keterangan:</strong> Simpanan wajib dibayarkan setiap akhir bulan/gaji karyawan
    </p>
</div>

<!-- Modals -->
<div id="addSimpananModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-2/3 lg:w-1/2 shadow-lg rounded-lg bg-white">
        <!-- Modal content will be loaded by JavaScript -->
    </div>
</div>
@endsection

@section('scripts')
<script>
// Show add simpanan modal
function showAddSimpananModal() {
    const modalContent = `
        <div class="relative bg-white rounded-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Form Tambah Simpanan</h3>
                <button onclick="closeModal('addSimpananModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form onsubmit="saveSimpanan(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Anggota *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                <option value="">Pilih Anggota</option>
                                <option value="1">Putra Pratama</option>
                                <option value="2">Sindi Nur Amelia</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal *</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" value="${new Date().toISOString().split('T')[0]}" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori *</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                <option value="Wajib">Wajib</option>
                                <option value="Pokok">Pokok</option>
                                <option value="Sukarela">Sukarela</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal/Setoran *</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="150000" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan/Keterangan</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" rows="2" placeholder="Contoh: Simpanan wajib akhir bulan/gaji karyawan"></textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addSimpananModal')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                            Tutup
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.getElementById('addSimpananModal').querySelector('.relative').innerHTML = modalContent;
    document.getElementById('addSimpananModal').classList.remove('hidden');
}

// Detail simpanan
function detailSimpanan(id) {
    const simpananData = [
        {id: 1, nama: "Sindi Nur Amelia", kategori: "Wajib", tanggal: "23 September 2024", nominal: 350000, catatan: "Simpanan wajib akhir bulan/gaji karyawan"},
        {id: 2, nama: "Putra Pratama", kategori: "Wajib", tanggal: "24 September 2024", nominal: 150000, catatan: "Simpanan wajib akhir bulan/gaji karyawan"},
        {id: 3, nama: "Putra Pratama", kategori: "Pokok", tanggal: "25 September 2024", nominal: 50000, catatan: "Simpanan pokok awal keanggotaan"},
        {id: 4, nama: "Sindi Nur Amelia", kategori: "Sukarela", tanggal: "26 September 2024", nominal: 100000, catatan: "Simpanan sukarela tambahan"}
    ];
    
    const data = simpananData.find(s => s.id === id);
    if (!data) return;
    
    alert(`Detail Simpanan:\n\nNama: ${data.nama}\nKategori: ${data.kategori}\nTanggal: ${data.tanggal}\nNominal: ${formatCurrency(data.nominal)}\n\nCatatan: ${data.catatan || '-'}`);
}

// Edit simpanan
function editSimpanan(id) {
    const modalContent = `
        <div class="relative bg-white rounded-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Form Ubah Simpanan</h3>
                <button onclick="closeModal('addSimpananModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <form onsubmit="updateSimpanan(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm bg-gray-50" disabled>
                                <option>Putra Pratama</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
                            <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" value="${new Date().toISOString().split('T')[0]}" required>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                            <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                <option value="Wajib" selected>Wajib</option>
                                <option value="Pokok">Pokok</option>
                                <option value="Sukarela">Sukarela</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nominal/Setoran *</label>
                            <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" value="150000" required>
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan/Keterangan</label>
                        <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" rows="2">Simpanan wajib akhir bulan/gaji karyawan</textarea>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeModal('addSimpananModal')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                            Tutup
                        </button>
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    `;
    
    document.getElementById('addSimpananModal').querySelector('.relative').innerHTML = modalContent;
    document.getElementById('addSimpananModal').classList.remove('hidden');
}

// Save simpanan
function saveSimpanan(event) {
    event.preventDefault();
    closeModal('addSimpananModal');
    showAlert('Simpanan berhasil ditambahkan', 'success');
}

// Update simpanan
function updateSimpanan(event) {
    event.preventDefault();
    closeModal('addSimpananModal');
    showAlert('Simpanan berhasil diperbarui', 'success');
}

// Delete simpanan
function deleteSimpanan(id) {
    if (confirm('Apakah Anda yakin ingin menghapus simpanan ini?')) {
        showAlert('Simpanan berhasil dihapus', 'success');
    }
}

// Modal functions
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('addSimpananModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal('addSimpananModal');
    }
});
</script>
@endsection