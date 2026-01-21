@extends('layouts.master')

@section('title', 'Simpanan - Koperasi Merah Putih')

@section('content')

    <div class="mx-10 px-4 bgwhite/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class=" px-6 py-2  flex justify-between items-center">
                <div class="border-l-8 border-l-green-500 pl-4">
                    <div class="text-2xl text-white text-4xl text-shadow-lg uppercase font-extrabold tracking-wider">Data
                        Simpanan</div>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search..." id="search-simpanan"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    <a type="a" href="{{ route('simpanan.create') }}"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center  text-decoration-none shadow-md text-lg uppercase font-semibold">
                        <i class="fas fa-plus mr-2"></i>
                         Tambah Simpanan
                    </a>
                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-4">
        <p class="text-slate-600 pl-4 ">Kelola Data Simpanan dengan Teliti !</p>
        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-md">

            <div class="overflow-x-auto flex p-10 px-10">
                <table class="text-center min-w-full overflow-hidden space-y-4">
                    <thead class="bg-orange-300">
                        <tr
                            class="text-white [&>th]:px-6 [&>th]:py-3 [&>th]:text-left [&>th]:text-sm [&>th]:font-semibold [&>th]:uppercase [&>th]:tracking-wider">
                            <th>NO.</th>
                            <th>NAMA ANGGOTA</th>
                            <th>KATEGORI</th>
                            <th>TANGGAL</th>
                            <th>NOMINAL/SETORAN</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($simpanans as $simpanan)
                        <tr class="[&>td]:text-sm [&>td]:px-6 [&>td]:py-4 border-b hover:bg-gray-50 ">
                            <td class="font-medium">{{ $loop->iteration }}</td>
                            <td>
                                <div class="flex items-center">
                                    <div class="h-8 w-8 rounded-full bg-pink-100 flex items-center justify-center mr-3">
                                        <span class="text-pink-600 font-bold text-sm">S</span>
                                    </div>
                                    <div class="w-full">
                                        <div class=" text-gray-900 font-semibold">{{ $simpanan->member->nama_lengkap }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if ($simpanan->simpananDetails->first()->jenis == 'wajib')
                                    <span class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-blue-100 text-blue-800">
                                @elseif ($simpanan->simpananDetails->first()->jenis == 'pokok')
                                    <span class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-green-100 text-green-800">
                                @else
                                    <span class="px-2 py-1 text-[1rem] font-semibold uppercase rounded bg-orange-100 text-orange-800">
                                @endif
                                            {{ $simpanan->simpananDetails->first()->jenis }}
                                </span>
                            </td>
                            <td>{{ Carbon\Carbon::parse($simpanan->simpananDetails->first()->tanggal)->format('d F Y') }}</td>
                            <td class="font-bold text-red-600">Rp {{ number_format($simpanan->simpananDetails->sum('saldo'), 0, ',', '.') }}</td>
                            <td>
                                <div class="flex justify-center items-center gap-3">
                                    <button onclick="detailSimpanan(1)" class="text-blue-600 hover:text-blue-900"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editSimpanan(1)" class="text-yellow-600 hover:text-yellow-900"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteSimpanan(1)" class="text-red-600 hover:text-red-900"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100">
        <p class="text-lg text-blue-800 flex items-center pt-2">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Keterangan : </strong> Simpanan wajib dibayarkan setiap akhir bulan/gaji karyawan
        </p>
    </div>

    </div>
@endsection

@section('scripts')
    <script>
        // Search fungsi
        document.getElementById('search-simpanan').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('table tbody tr    ');

            rows.forEach(function(row) {
                let namaAnggota = row.cells[1].textContent.toLowerCase();
                let jenis = row.cells[2].textContent.toLowerCase();
                let tanggal = row.cells[3].textContent.toLowerCase();
                if (namaAnggota.indexOf(filter) > -1 || jenis.indexOf(filter) > -1 || tanggal.indexOf(filter) > -1) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        @if (session('success'))
            {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    showConfirma: false
                });
            }
        @elseif (session('error')) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    showConfirma: true
                });
            }
        @endif


        // Edit simpanan
    //     function editSimpanan(id) {
    //         const modalContent = `
    //     <div class="relative bg-white rounded-lg">
    //         <div class="flex justify-between items-center p-4 border-b">
    //             <h3 class="text-lg font-bold text-gray-800">Form Ubah Simpanan</h3>
    //             <button onclick="closeModal('addSimpananModal')" class="text-gray-400 hover:text-gray-600">
    //                 <i class="fas fa-times text-xl"></i>
    //             </button>
    //         </div>
            
    //         <div class="p-6">
    //             <form onsubmit="updateSimpanan(event)">
    //                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    //                     <div>
    //                         <label class="block text-sm font-medium text-gray-700 mb-1">Anggota</label>
    //                         <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm bg-gray-50" disabled>
    //                             <option>Putra Pratama</option>
    //                         </select>
    //                     </div>
                        
    //                     <div>
    //                         <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal</label>
    //                         <input type="date" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" value="${new Date().toISOString().split('T')[0]}" required>
    //                     </div>
    //                 </div>
                    
    //                 <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    //                     <div>
    //                         <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
    //                         <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
    //                             <option value="Wajib" selected>Wajib</option>
    //                             <option value="Pokok">Pokok</option>
    //                             <option value="Sukarela">Sukarela</option>
    //                         </select>
    //                     </div>
                        
    //                     <div>
    //                         <label class="block text-sm font-medium text-gray-700 mb-1">Nominal/Setoran *</label>
    //                         <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" value="150000" required>
    //                     </div>
    //                 </div>
                    
    //                 <div class="mb-6">
    //                     <label class="block text-sm font-medium text-gray-700 mb-1">Catatan/Keterangan</label>
    //                     <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" rows="2">Simpanan wajib akhir bulan/gaji karyawan</textarea>
    //                 </div>
                    
    //                 <div class="flex justify-end space-x-3">
    //                     <button type="button" onclick="closeModal('addSimpananModal')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
    //                         Tutup
    //                     </button>
    //                     <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
    //                         Simpan Perubahan
    //                     </button>
    //                 </div>
    //             </form>
    //         </div>
    //     </div>
    // `;

    //         document.getElementById('addSimpananModal').querySelector('.relative').innerHTML = modalContent;
    //         document.getElementById('addSimpananModal').classList.remove('hidden');
    //     }

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
