@extends('layouts.master')

@section('title', 'Edit Simpanan - Koperasi Merah Putih')

@section('content')
    <div class="mx-10 px-4 bg-white/40 backdrop-blur-2xl rounded-2xl py-8 shadow-2xl">
        <!-- Header Card -->
        <div class="mb-2">
            <div class="px-6 py-2 flex justify-between items-center">
                <div class="border-l-8 border-l-yellow-500 pl-4">
                    <div class="text-2xl text-white text-4xl text-shadow-lg uppercase font-extrabold tracking-wider">
                        Edit Simpanan
                    </div>
                    <p class="text-slate-600 mt-2">Kode: {{ $transaksi->no_transaksi_sp }}</p>
                </div>
            </div>
        </div>
        <hr class="m-0 p-0 mb-6">

        <!-- Form Edit -->
        <div class="bg-white rounded-lg border border-gray-200 p-6 shadow-sm">
            <form action="{{ route('simpanan.update', $transaksi->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Informasi Anggota (Readonly) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Anggota</label>
                        <div class="bg-gray-50 border border-gray-300 rounded px-3 py-2 text-sm">
                            <strong>{{ $transaksi->member->nama_lengkap }}</strong>
                            <p class="text-gray-600 text-xs mt-1">
                                No. Anggota: {{ $transaksi->member->nomor_anggota ?? '-' }}
                            </p>
                        </div>
                        <input type="hidden" name="member_id" value="{{ $transaksi->member_id }}">
                    </div>

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                        <input type="date" 
                               id="tanggal" 
                               name="tanggal" 
                               value="{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('Y-m-d') }}" 
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                               required>
                        @error('tanggal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Jenis Simpanan -->
                    <div>
                        <label for="jenis" class="block text-sm font-medium text-gray-700 mb-2">Jenis Simpanan</label>
                        <select id="jenis" 
                                name="jenis" 
                                class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                                required>
                            <option value="wajib" {{ $transaksi->simpananDetails->first()->jenis == 'wajib' ? 'selected' : '' }}>Wajib</option>
                            <option value="pokok" {{ $transaksi->simpananDetails->first()->jenis == 'pokok' ? 'selected' : '' }}>Pokok</option>
                            <option value="sukarela" {{ $transaksi->simpananDetails->first()->jenis == 'sukarela' ? 'selected' : '' }}>Sukarela</option>
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nominal -->
                    <div>
                        <label for="nominal" class="block text-sm font-medium text-gray-700 mb-2">Nominal (Rp)</label>
                        <input type="number" 
                               id="nominal" 
                               name="nominal" 
                               value="{{ $transaksi->Nominal }}" 
                               min="0"
                               step="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                               required>
                        @error('nominal')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                    <textarea id="keterangan" 
                              name="keterangan" 
                              rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm"
                              placeholder="Tambahkan keterangan jika perlu...">{{ $transaksi->Keterangan }}</textarea>
                    @error('keterangan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status (Opsional, jika ada field status) -->
                @if($transaksi->simpananDetails->first()->status)
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-red-500 focus:border-red-500 text-sm">
                        <option value="aktif" {{ $transaksi->simpananDetails->first()->status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ $transaksi->simpananDetails->first()->status == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <a href="{{ route('simpanan.index', $transaksi->id) }}" 
                       class="px-4 py-2 border border-gray-300 rounded text-gray-700 hover:bg-gray-50 text-sm font-medium">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <!-- Informasi Penting -->
        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800 flex items-start">
                <i class="fas fa-exclamation-triangle mr-2 mt-0.5"></i>
                <span>
                    <strong>Perhatian:</strong> Perubahan data simpanan akan tercatat dalam history transaksi. 
                    Pastikan data yang diubah sudah benar.
                </span>
            </p>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    // Format nominal saat input
    document.getElementById('nominal').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^\d]/g, '');
        if (value) {
            e.target.value = parseInt(value).toLocaleString('id-ID');
        }
    });

    // Validasi form sebelum submit
    document.querySelector('form').addEventListener('submit', function(e) {
        const nominal = document.getElementById('nominal').value;
        if (parseInt(nominal.replace(/\./g, '')) <= 0) {
            e.preventDefault();
            alert('Nominal harus lebih dari 0');
        }
    });
</script>
@endsection