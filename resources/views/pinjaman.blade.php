@extends('layouts.master')

@section('title', 'Pinjaman - Koperasi Merah Putih')

@section('content')
    <div class="mx-10 px-4 bgwhite/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-6">
            <div class="px-6 py-2 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl text-black uppercase font-extrabold tracking-wider">Data Pinjaman</h2>
                    <p class="text-sm text-gray-600">Kelola data pinjaman anggota</p>
                </div>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" placeholder="Search..."
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm w-64">
                        <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                    </div>
                    <button type="button"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded flex items-center text-sm"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Pinjaman
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-md">

            <div class="overflow-x-auto flex p-10">
                <table class="text-center min-w-full border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-orange-300 py-3">
                        <tr>
                            <th class="text-left">NO.</th>
                            <th class="text-left">NAMA ANGGOTA</th>
                            <th class="text-left">TANGGAL</th>
                            <th class="text-left">TOTAL PINJAMAN</th>
                            <th class="text-left">JENIS PINJAMAN</th>
                            <th class="text-left">LAMA BAYAR</th>
                            <th class="text-left">JATUH TEMPO</th>
                            <th class="text-left">STATUS</th>
                            <th class="text-left">ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="hover:bg-gray-50">
                            <td class="font-medium">1</td>
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
                            <td>24 September 2024</td>
                            <td class="font-bold text-red-600">Rp 1.000.000</td>
                            <td>
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">
                                    Jangka Panjang
                                </span>
                            </td>
                            <td>24x</td>
                            <td>-</td>
                            <td>
                                <span class="status-badge status-aktif">AKTIF</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="viewDetail(1)" class="text-blue-600 hover:text-blue-900"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editPinjaman(1)" class="text-yellow-600 hover:text-yellow-900"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deletePinjaman(1)" class="text-red-600 hover:text-red-900"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <tr class="hover:bg-gray-50">
                            <td class="font-medium">2</td>
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
                            <td>23 September 2024</td>
                            <td class="font-bold text-red-600">Rp 100.000</td>
                            <td>
                                <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">
                                    Jangka Pendek
                                </span>
                            </td>
                            <td>10x</td>
                            <td>-</td>
                            <td>
                                <span class="status-badge status-selesai">SELESAI/LUNAS</span>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button onclick="viewDetail(2)" class="text-blue-600 hover:text-blue-900"
                                        title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button onclick="editPinjaman(2)" class="text-yellow-600 hover:text-yellow-900"
                                        title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deletePinjaman(2)" class="text-red-600 hover:text-red-900"
                                        title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

    </div>

    <!-- Modal tambah pinjaman-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content rounded-3xl overflow-hidden">
                <div class="px-8 py-4 flex justify-start items-center space-x-3 bg-blue-600">
                    <I class="fas fa-plus-circle text-white text-3xl"></I>
                    <h1 class="modal-title fs-5 uppercase font-bold" id="exampleModalLabel">Tambah Pinjaman</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-6">
                        <form action="" method="POST">
                            @csrf   
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota *</label>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                        required>
                                        <option value="">Pilih Anggota</option>
                                        <option value="1">Putra Pratama</option>
                                        <option value="2">Sindi Nur Amelia</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pinjaman *</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                        placeholder="1000000" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pinjaman *</label>
                                    <select
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                        required>
                                        <option value="pendek">Jangka Pendek</option>
                                        <option value="panjang">Jangka Panjang</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lama (Bulan) *</label>
                                    <input type="number"
                                        class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                        placeholder="12" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bunga per Tahun (%) *</label>
                                <input type="number" step="0.01"
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    placeholder="12" required>
                            </div>

                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                                <textarea
                                    class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                    rows="2" placeholder="Tambahkan catatan jika perlu"></textarea>
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" data-bs-dismiss="modal"
                                    class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Template for Detail Cards -->
    <template id="detailCardTemplate">
        <div class="bg-white rounded-lg border border-gray-200 p-4">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-sm text-gray-500">#TITLE#</p>
                    <p class="text-2xl font-bold #COLOR#">#VALUE#</p>
                </div>
                <i class="fas #ICON# text-2xl text-gray-300"></i>
            </div>
        </div>

    </template>
@endsection

@section('scripts')
    <script>
        // Pinjaman data
        const pinjamanData = [{
                id: 1,
                nama: "Putra Pratama",
                telepon: "085748278940",
                tanggal: "24 September 2024",
                total_pinjaman: 1000000,
                jenis_pinjaman: "Jangka Panjang",
                lama_bayar: "24x",
                jatuh_tempo: "-",
                status: "AKTIF",
                angsuran_per_bulan: 46500,
                total_bunga: 116000,
                total_pembayaran: 1116000,
                catatan: "",
                jenis_kelamin: "Laki-Laki",
                ttl: "Sidoarjo, 24-09-2024",
                alamat: "Waru",
                angsuran: [{
                        ke: 1,
                        batas_bayar: "24 Oktober 2024",
                        nominal: 46500,
                        tanggal_bayar: "",
                        status: "BELUM LUNAS"
                    },
                    {
                        ke: 2,
                        batas_bayar: "24 November 2024",
                        nominal: 46500,
                        tanggal_bayar: "",
                        status: "BELUM LUNAS"
                    },
                    {
                        ke: 3,
                        batas_bayar: "24 Desember 2024",
                        nominal: 46500,
                        tanggal_bayar: "",
                        status: "BELUM LUNAS"
                    }
                ]
            },
            {
                id: 2,
                nama: "Sindi Nur Amelia",
                telepon: "081234567890",
                tanggal: "23 September 2024",
                total_pinjaman: 100000,
                jenis_pinjaman: "Jangka Pendek",
                lama_bayar: "10x",
                jatuh_tempo: "-",
                status: "SELESAI/LUNAS",
                angsuran_per_bulan: 10500,
                total_bunga: 5000,
                total_pembayaran: 105000,
                catatan: "Tes edit catatan",
                jenis_kelamin: "Perempuan",
                ttl: "Surabaya, 23-09-2024",
                alamat: "Jl. Melati No. 5",
                angsuran: [{
                        ke: 1,
                        batas_bayar: "23 Oktober 2024",
                        nominal: 10500,
                        tanggal_bayar: "20 Oktober 2024",
                        status: "LUNAS"
                    },
                    {
                        ke: 2,
                        batas_bayar: "23 November 2024",
                        nominal: 10500,
                        tanggal_bayar: "22 November 2024",
                        status: "LUNAS"
                    }
                ]
            }
        ];

        // Show add pinjaman modal
        function showAddPinjamanModal() {
            const modalContent = `
            <div class="relative bg-white rounded-lg">
                <div class="flex justify-between items-center p-4 border-b">
                    <h3 class="text-lg font-bold text-gray-800">Tambah Pinjaman Baru</h3>
                    <button onclick="closeModal('addPinjamanModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <form onsubmit="savePinjaman(event)">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anggota *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                    <option value="">Pilih Anggota</option>
                                    <option value="1">Putra Pratama</option>
                                    <option value="2">Sindi Nur Amelia</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Pinjaman *</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="1000000" required>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Pinjaman *</label>
                                <select class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" required>
                                    <option value="pendek">Jangka Pendek</option>
                                    <option value="panjang">Jangka Panjang</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lama (Bulan) *</label>
                                <input type="number" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="12" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bunga per Tahun (%) *</label>
                            <input type="number" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" placeholder="12" required>
                        </div>
                        
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm" rows="2" placeholder="Tambahkan catatan jika perlu"></textarea>
                        </div>
                        
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeModal('addPinjamanModal')" class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    `;

            document.getElementById('addPinjamanModal').querySelector('.relative').innerHTML = modalContent;
            document.getElementById('addPinjamanModal').classList.remove('hidden');
        }

        // View detail pinjaman
        function viewDetail(id) {
            const data = pinjamanData.find(p => p.id === id);
            if (!data) return;

            // Show detail cards
            const detailCards = document.getElementById('detailCards');
            const template = document.getElementById('detailCardTemplate');

            detailCards.innerHTML = '';

            // Card 1: Bayar per Angsuran
            let card1 = template.content.cloneNode(true);
            let html1 = card1.querySelector('div').outerHTML;
            html1 = html1.replace('#TITLE#', 'Bayar per Angsuran')
                .replace('#VALUE#', formatCurrency(data.angsuran_per_bulan))
                .replace('#COLOR#', 'text-red-600')
                .replace('#ICON#', 'fa-money-bill-wave');
            detailCards.innerHTML += html1;

            // Card 2: Total Bunga
            let card2 = template.content.cloneNode(true);
            let html2 = card2.querySelector('div').outerHTML;
            html2 = html2.replace('#TITLE#', 'Total Bunga')
                .replace('#VALUE#', formatCurrency(data.total_bunga))
                .replace('#COLOR#', 'text-yellow-600')
                .replace('#ICON#', 'fa-percentage');
            detailCards.innerHTML += html2;

            // Card 3: Total Pembayaran
            let card3 = template.content.cloneNode(true);
            let html3 = card3.querySelector('div').outerHTML;
            html3 = html3.replace('#TITLE#', 'Total Pembayaran')
                .replace('#VALUE#', formatCurrency(data.total_pembayaran))
                .replace('#COLOR#', 'text-green-600')
                .replace('#ICON#', 'fa-calculator');
            detailCards.innerHTML += html3;

            detailCards.classList.remove('hidden');

            // Show catatan jika ada
            if (data.catatan) {
                const catatanCard = `
            <div class="col-span-1 md:col-span-3 bg-white rounded-lg border border-gray-200 p-4 mt-4">
                <p class="text-sm text-gray-500 mb-2">Catatan:</p>
                <p class="text-gray-700">${data.catatan}</p>
            </div>
        `;
                detailCards.innerHTML += catatanCard;
            }

            // Show detail modal
            const modalContent = `
        <div class="relative bg-white rounded-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="text-lg font-bold text-gray-800">Detail Pinjaman & Angsuran : ${data.nama}</h3>
                <button onclick="closeModal('detailPinjamanModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="p-6">
                <!-- Info Anggota -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">NAMA ANGGOTA</p>
                            <p class="text-lg font-bold text-gray-800">${data.nama}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">JENIS KELAMIN</p>
                            <p class="text-lg text-gray-800">${data.jenis_kelamin}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">TEMPAT, TANGGAL LAHIR</p>
                            <p class="text-lg text-gray-800">${data.ttl}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">NO. TELP/WA</p>
                            <p class="text-lg text-gray-800">${data.telepon} / WhatsApp</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-500">TOTAL PINJAMAN</p>
                            <p class="text-lg font-bold text-red-600">${formatCurrency(data.total_pinjaman)}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">JENIS PINJAMAN</p>
                            <p class="text-lg text-gray-800">${data.jenis_pinjaman} (${data.lama_bayar})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">TOTAL DIBAYAR</p>
                            <p class="text-lg font-bold text-green-600">Rp 0</p>
                        </div>
                        <div class="flex space-x-4">
                            <div>
                                <p class="text-sm text-gray-500">JATUH TEMPO</p>
                                <p class="text-lg text-gray-800">-</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">STATUS PINJAMAN</p>
                                <span class="status-badge ${data.status === 'AKTIF' ? 'status-aktif' : 'status-selesai'}">
                                    ${data.status === 'AKTIF' ? 'AKTIF/BELUM LUNAS' : data.status}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Alamat -->
                <div class="mb-6 p-4 bg-gray-50 rounded">
                    <p class="text-sm text-gray-500 mb-1">ALAMAT</p>
                    <p class="text-gray-800">${data.alamat}</p>
                </div>
                
                <!-- Angsuran Table -->
                <div class="mb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="text-lg font-bold text-gray-800">Rincian Angsuran</h4>
                        <div class="flex items-center space-x-3">
                            <select class="px-3 py-1 border border-gray-300 rounded text-sm">
                                <option>Show: 100 entries</option>
                            </select>
                            <input type="text" placeholder="Search..." class="px-3 py-1 border border-gray-300 rounded text-sm">
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="table-gudang">
                            <thead>
                                <tr>
                                    <th>ANGSURAN KE</th>
                                    <th>BATAS BAYAR</th>
                                    <th>NOMINAL HARUS DIBAYAR</th>
                                    <th>TANGGAL BAYAR</th>
                                    <th>STATUS BAYAR</th>
                                    <th>TRANSAKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.angsuran.map(item => {
                                    const statusClass = item.status === 'LUNAS' ? 'status-aktif' : 'status-belum';
                                    return `
                                                <tr>
                                                    <td class="text-center">${item.ke}</td>
                                                    <td>${item.batas_bayar}</td>
                                                    <td class="font-bold text-red-600">${formatCurrency(item.nominal)}</td>
                                                    <td>${item.tanggal_bayar || '-'}</td>
                                                    <td>
                                                        <span class="status-badge ${statusClass}">
                                                            ${item.status}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="flex space-x-2">
                                                            ${item.status === 'BELUM LUNAS' ? `
                                                    <button onclick="bayarAngsuran(${data.id}, ${item.ke})" class="text-green-600 hover:text-green-900" title="Bayar">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </button>
                                                    ` : ''}
                                                            <button class="text-blue-600 hover:text-blue-900" title="Cetak">
                                                                <i class="fas fa-print"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="flex justify-end">
                    <button onclick="closeModal('detailPinjamanModal')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    `;

            document.getElementById('detailPinjamanModal').querySelector('.relative').innerHTML = modalContent;
            document.getElementById('detailPinjamanModal').classList.remove('hidden');
        }

        // Edit pinjaman
        function editPinjaman(id) {
            showAlert('Fitur edit dalam pengembangan', 'info');
        }

        // Delete pinjaman
        function deletePinjaman(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pinjaman ini?')) {
                showAlert('Pinjaman berhasil dihapus', 'success');
            }
        }

        // Bayar angsuran
        function bayarAngsuran(pinjamanId, angsuranKe) {
            if (confirm(`Bayar angsuran ke-${angsuranKe}?`)) {
                showAlert(`Angsuran ke-${angsuranKe} berhasil dibayar`, 'success');
            }
        }

        // Save pinjaman
        function savePinjaman(event) {
            event.preventDefault();
            closeModal('addPinjamanModal');
            showAlert('Pinjaman berhasil ditambahkan', 'success');
        }

        // Modal functions
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('addPinjamanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('addPinjamanModal');
            }
        });

        document.getElementById('detailPinjamanModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal('detailPinjamanModal');
            }
        });
    </script>
@endsection
