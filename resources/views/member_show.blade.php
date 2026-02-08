@extends('layouts.master')

@section('content')
    <main>
        <div class="max-w-7xl mx-auto bg-white mt-10 rounded-lg overflow-hidden shadow">
            <div class="text-2xl font-bold mb-6 bg-black px-4 py-3 uppercase text-white">
                <i class="fa-solid fa-users-rectangle mr-2"></i>
                Detail Member
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mx-8 mb-8 flex flex-col items-center">
                    <p class="font-semibold justify-self-center">Foto KTP</p>
                    @forelse (($member->foto_ktp ?? []) as $foto)
                        <img src="{{ asset('storage/' . $foto) }}" alt="Foto KTP" class="w-48 h-48 object-cover rounded-md mt-2 border border-gray-300">
                    @empty
                        <i class="fa-regular fa-address-card text-[20rem] text-gray-400"></i>
                    @endforelse
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 px-8 py-8 pt-12 relative">
                    @if ($modul === 'pinjaman')
                        <a href="{{ route('member.banned', $member->id) }}"
                            class="text-white text-sm font-semibold absolute top-0 right-5 p-2 rounded-md uppercase bg-red-600 hover:bg-red-700 no-underline!">
                            <i class="fa-solid fa-user-slash mr-2"></i>
                            <span class="">Ban Member</span></a>
                    @endif
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">ID Member:</p>
                        <p>{{ $member->nik }}</p>
                    </div>
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">Nama Lengkap:</p>
                        <p>{{ $member->nama_lengkap }}</p>
                    </div>
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">Alamat:</p>
                        <p>{{ $member->alamat }}</p>
                    </div>
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">No. HP:</p>
                        <p>{{ $member->nomor_hp }}</p>
                    </div>
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">Email:</p>
                        <p>{{ $member->email }}</p>
                    </div>
                    <div>
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">Tanggal Bergabung:</p>
                        <p>{{ $member->created_at->format('d F Y') }}</p>
                    </div>
                    <div class="">
                        <p class="font-semibold bg-slate-400/30 text-white px-2 uppercase">Status:</p>
                        <p class="capitalize">
                            @if ($member->status === 'aktif')
                                <span class="text-green-600 font-semibold">Aktif</span>
                            @elseif($member->status === 'tidak aktif')
                                <span class="text-yellow-600 font-semibold">Tidak Aktif</span>
                            @else
                                <span class="text-red-600 font-semibold">Banned</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            <hr class="mx-5">
            <div class="mx-8 mb-8 flex space-x-4 justify-end">

                <a href="{{ url()->previous() }}"
                    class="text-white font-semibold p-2 rounded-md uppercase bg-blue-600 hover:bg-blue-700 no-underline!">
                    <i class="fa-solid fa-arrow-left mr-2"></i>
                    <span class="">Kembali</span>
                </a>
            </div>
    </main>
@endsection
