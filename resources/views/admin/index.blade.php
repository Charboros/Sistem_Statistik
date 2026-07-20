<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Sistem Statistik Layanan Disdukcapil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-[#1e4e8c] min-h-screen text-gray-100 font-sans antialiased pb-20">

    <!-- Header -->
    <header class="bg-[#1a437a]/95 backdrop-blur-md sticky top-0 z-50 border-b border-white/10 shadow-[0_4px_20px_rgba(0,0,0,0.15)] mb-8">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-tegal.png') }}" alt="Logo Kabupaten Tegal" class="w-10 h-auto drop-shadow-md">
                <div class="flex items-center space-x-2">
                    <h1 class="font-bold text-white leading-tight">Dinas <span class="text-blue-300">Dukcapil</span> Kabupaten Tegal</h1>
                    <span class="text-[10px] bg-orange-500 text-white px-2 py-0.5 rounded-full font-bold shadow-sm">Admin</span>
                </div>
            </div>
            <div>
                <a href="{{ route('home') }}" class="text-sm text-blue-300 hover:text-white transition-colors">&larr; Kembali ke halaman statistik</a>
            </div>
        </div>
    </header>

    <div class="max-w-6xl mx-auto">

        <form action="{{ route('admin.store') }}" method="POST" x-data="adminData">
            @csrf
            
            <!-- Toolbar -->
            <div class="flex flex-col md:flex-row justify-between items-end mb-6 px-4 space-y-4 md:space-y-0">
                <div>
                    <label class="block text-xs font-bold text-blue-200 uppercase tracking-wide mb-1">Tanggal data</label>
                    <input type="date" name="tanggal_data" value="{{ $tanggalData }}" class="border border-blue-400 rounded-lg px-3 py-1.5 text-sm w-48 text-gray-800 font-semibold bg-white focus:outline-none focus:ring-2 focus:ring-blue-300 shadow-sm">
                </div>
                <div class="flex space-x-3 text-sm items-center">
                    <!-- Form Tambah Lokasi -->
                    <div x-data="{ openAddLoc: false }" class="relative">
                        <button type="button" @click="openAddLoc = !openAddLoc" class="px-3 py-1.5 bg-blue-50 text-blue-600 border border-blue-200 rounded shadow-sm hover:bg-blue-100 font-bold flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span>Lokasi</span>
                        </button>
                        
                        <div x-show="openAddLoc" @click.away="openAddLoc = false" class="absolute top-full right-0 mt-2 w-72 bg-white border border-gray-200 shadow-lg rounded-lg p-3 z-50">
                            <h4 class="text-xs font-bold text-gray-700 mb-2">Tambah Lokasi Baru</h4>
                            <div class="flex flex-col space-y-2">
                                <select name="kategori_add" id="kategori_add" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-400">
                                    <option value="kecamatan">Kecamatan</option>
                                    <option value="mpp">MPP</option>
                                    <option value="dinas">Dinas</option>
                                </select>
                                <div class="flex space-x-2">
                                    <input type="text" name="new_lokasi" id="new_lokasi" placeholder="Nama lokasi..." class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-400">
                                    <button type="button" onclick="submitAddLokasi()" class="px-3 py-1 bg-blue-500 text-white rounded text-sm font-bold hover:bg-blue-600">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Tambah Layanan -->
                    <div x-data="{ openAdd: false }" class="relative">
                        <button type="button" @click="openAdd = !openAdd" class="px-3 py-1.5 bg-green-50 text-green-600 border border-green-200 rounded shadow-sm hover:bg-green-100 font-bold flex items-center space-x-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <span>Layanan</span>
                        </button>
                        
                        <div x-show="openAdd" @click.away="openAdd = false" class="absolute top-full right-0 mt-2 w-64 bg-white border border-gray-200 shadow-lg rounded-lg p-3 z-50">
                            <h4 class="text-xs font-bold text-gray-700 mb-2">Tambah Jenis Layanan Baru</h4>
                            <div class="flex space-x-2">
                                <input type="text" name="new_layanan" id="new_layanan" placeholder="Nama layanan..." class="flex-1 border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:border-blue-400">
                                <button type="button" onclick="submitAddLayanan()" class="px-3 py-1 bg-blue-500 text-white rounded text-sm font-bold hover:bg-blue-600">Add</button>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 bg-sky-500 text-white rounded-lg shadow-sm hover:bg-sky-600 font-bold inline-block text-center cursor-pointer transition-colors">Muat ulang data awal</a>
                    <button type="submit" class="px-4 py-1.5 bg-emerald-500 text-white rounded-lg shadow-sm hover:bg-emerald-600 font-bold transition-colors">Simpan perubahan</button>
                </div>
            </div>

            @if(session('success'))
                <div class="mx-4 mb-4 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 px-4">
                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Total {{ $countKecamatan }} kecamatan</div>
                    <div class="text-3xl font-bold text-blue-600" x-text="getCategoryTotal('kecamatan').toLocaleString('id-ID')">{{ number_format($totalKecamatan, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Total MPP</div>
                    <div class="text-3xl font-bold text-orange-500" x-text="getCategoryTotal('mpp').toLocaleString('id-ID')">{{ number_format($totalMpp, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Total Dinas</div>
                    <div class="text-3xl font-bold text-teal-500" x-text="getCategoryTotal('dinas').toLocaleString('id-ID')">{{ number_format($totalDinas, 0, ',', '.') }}</div>
                </div>
                <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                    <div class="text-[10px] text-gray-400 font-bold uppercase mb-1">Total Keseluruhan</div>
                    <div class="text-3xl font-bold text-red-500" x-text="getCategoryTotal('semua').toLocaleString('id-ID')">{{ number_format($totalKeseluruhan, 0, ',', '.') }}</div>
                </div>
            </div>

            <!-- Search & Filter -->
            <div class="flex items-center space-x-4 mb-4 px-4">
                <input type="text" x-model="search" placeholder="Cari nama kecamatan / MPP / Dinas..." class="flex-1 border border-gray-200 rounded-full px-4 py-2 text-sm focus:outline-none focus:border-blue-300 focus:ring-1 focus:ring-blue-300">
                <div class="flex space-x-2 text-xs">
                    <button type="button" @click="filter = 'semua'" :class="filter === 'semua' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white border-gray-200 text-gray-500'" class="border rounded-full px-4 py-1.5 font-medium transition-colors">Semua</button>
                    <button type="button" @click="filter = 'kecamatan'" :class="filter === 'kecamatan' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white border-gray-200 text-gray-500'" class="border rounded-full px-4 py-1.5 font-medium transition-colors">Kecamatan</button>
                    <button type="button" @click="filter = 'mpp'" :class="filter === 'mpp' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white border-gray-200 text-gray-500'" class="border rounded-full px-4 py-1.5 font-medium transition-colors">MPP</button>
                    <button type="button" @click="filter = 'dinas'" :class="filter === 'dinas' ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-white border-gray-200 text-gray-500'" class="border rounded-full px-4 py-1.5 font-medium transition-colors">Dinas</button>
                </div>
            </div>

            <!-- Accordion List -->
            <div class="px-4 space-y-3">
                @foreach($locations as $nama_kecamatan => $data)
                <div class="bg-white border border-gray-100 rounded-lg shadow-sm overflow-hidden" 
                     x-show="showLocation('{{ strtolower($nama_kecamatan) }}', '{{ $data['kategori'] }}')"
                     x-data="{ expanded: false }">
                    
                    <!-- Card Header -->
                    <div class="flex justify-between items-center px-5 py-3 bg-white hover:bg-gray-50 transition-colors group">
                        <div class="flex items-center space-x-3 cursor-pointer" @click="expanded = !expanded">
                            <span class="font-bold text-gray-800 text-base">{{ $nama_kecamatan }}</span>
                            @if($data['kategori'] == 'kecamatan')
                                <span class="text-[9px] bg-gray-100 text-gray-400 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Kecamatan</span>
                            @elseif($data['kategori'] == 'mpp')
                                <span class="text-[9px] bg-orange-100 text-orange-500 font-bold px-2 py-0.5 rounded uppercase tracking-wider">MPP</span>
                            @else
                                <span class="text-[9px] bg-yellow-100 text-yellow-600 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Dinas</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button type="button" onclick="editLokasi('{{ $nama_kecamatan }}')" class="text-blue-400 hover:text-blue-600" title="Ubah Nama Lokasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                                <button type="button" onclick="deleteLokasi('{{ $nama_kecamatan }}')" class="text-red-300 hover:text-red-600" title="Hapus Lokasi Ini">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                            <span class="font-bold text-base {{ $data['kategori'] == 'mpp' ? 'text-orange-500' : ($data['kategori'] == 'dinas' ? 'text-yellow-600' : 'text-blue-600') }}" x-text="getLocationTotal('{{ $nama_kecamatan }}').toLocaleString('id-ID')">{{ number_format($data['total'], 0, ',', '.') }}</span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform cursor-pointer" @click="expanded = !expanded" :class="{'rotate-180': expanded}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div x-show="expanded" class="px-5 pb-5 pt-2 border-t border-gray-50">
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-x-6 gap-y-4 mt-2">
                            @foreach($layananTypes as $jenis)
                                @php
                                    $val = $data['layanans'][$jenis] ?? null;
                                @endphp
                                <div class="relative group">
                                    <div class="flex justify-between items-center mb-1">
                                        <label class="block text-xs font-semibold text-gray-600 uppercase tracking-wide">{{ $jenis }}</label>
                                        <button type="button" onclick="deleteLayanan('{{ $jenis }}')" class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition-opacity" title="Hapus Layanan Ini">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    @if($val)
                                        <input type="number" min="0" name="data[{{ $val['id'] }}]" x-model="locations['{{ addslashes($nama_kecamatan) }}'].layanans['{{ addslashes($jenis) }}'].jumlah" class="w-full text-base border-0 border-b border-gray-300 focus:ring-0 focus:border-blue-500 px-1 py-1 text-left font-bold text-gray-800 bg-transparent">
                                    @else
                                        <!-- Fallback jika data bolong -->
                                        <input type="number" min="0" disabled value="0" class="w-full text-base border-0 border-b border-gray-200 focus:ring-0 px-1 py-1 text-left font-bold text-gray-400 bg-transparent cursor-not-allowed" title="Data belum ada, silakan reset form">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
        </form>
    </div>

    <!-- Hidden Forms for Actions -->
    <form id="form-update-lokasi" method="POST" action="{{ route('admin.update-lokasi') }}" class="hidden">
        @csrf
        @method('PUT')
        <input type="hidden" name="old_lokasi" id="put_old_lokasi">
        <input type="hidden" name="new_lokasi" id="put_new_lokasi">
    </form>

    <form id="form-delete-lokasi" method="POST" action="{{ route('admin.delete-lokasi') }}" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="lokasi_to_delete" id="del_lokasi_name">
    </form>

    <form id="form-delete-layanan" method="POST" action="{{ route('admin.delete-layanan') }}" class="hidden">
        @csrf
        @method('DELETE')
        <input type="hidden" name="layanan_to_delete" id="del_layanan_name">
    </form>

    <form id="form-add-lokasi" method="POST" action="{{ route('admin.add-lokasi') }}" class="hidden">
        @csrf
        <input type="hidden" name="new_lokasi" id="post_new_lokasi">
        <input type="hidden" name="kategori" id="post_kategori">
    </form>

    <form id="form-add-layanan" method="POST" action="{{ route('admin.add-layanan') }}" class="hidden">
        @csrf
        <input type="hidden" name="new_layanan" id="post_new_layanan">
    </form>

    <script>
        function editLokasi(oldNama) {
            const newNama = prompt('Masukkan nama lokasi baru:', oldNama);
            if (!newNama || newNama.trim() === '' || newNama === oldNama) return;

            document.getElementById('put_old_lokasi').value = oldNama;
            document.getElementById('put_new_lokasi').value = newNama.trim();
            document.getElementById('form-update-lokasi').submit();
        }

        function deleteLokasi(lokasi) {
            if (!confirm('Yakin ingin menghapus lokasi "' + lokasi + '" beserta seluruh datanya?')) return;
            
            document.getElementById('del_lokasi_name').value = lokasi;
            document.getElementById('form-delete-lokasi').submit();
        }

        function deleteLayanan(jenis) {
            if (!confirm('Yakin ingin menghapus layanan "' + jenis + '" dari semua lokasi? Semua data untuk layanan ini akan hilang!')) return;
            
            document.getElementById('del_layanan_name').value = jenis;
            document.getElementById('form-delete-layanan').submit();
        }

        function submitAddLokasi() {
            const newLokasi = document.getElementById('new_lokasi').value;
            const kategori = document.getElementById('kategori_add').value;
            if (!newLokasi || newLokasi.trim() === '') return alert('Nama lokasi harus diisi!');

            document.getElementById('post_new_lokasi').value = newLokasi.trim();
            document.getElementById('post_kategori').value = kategori;
            document.getElementById('form-add-lokasi').submit();
        }

        function submitAddLayanan() {
            const newLayanan = document.getElementById('new_layanan').value;
            if (!newLayanan || newLayanan.trim() === '') return alert('Nama layanan harus diisi!');

            document.getElementById('post_new_layanan').value = newLayanan.trim();
            document.getElementById('form-add-layanan').submit();
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('adminData', () => ({
                search: '',
                filter: 'semua',
                locations: @json($locations),
                
                showLocation(name, category) {
                    const matchSearch = name.includes(this.search.toLowerCase());
                    const matchFilter = this.filter === 'semua' || this.filter === category;
                    return matchSearch && matchFilter;
                },

                getLocationTotal(locName) {
                    let total = 0;
                    const loc = this.locations[locName];
                    if (loc && loc.layanans) {
                        for (const key in loc.layanans) {
                            total += parseInt(loc.layanans[key]?.jumlah || 0);
                        }
                    }
                    return total;
                },

                getCategoryTotal(category) {
                    let total = 0;
                    for (const locName in this.locations) {
                        const loc = this.locations[locName];
                        if (category === 'semua' || loc.kategori === category) {
                            for (const key in loc.layanans) {
                                total += parseInt(loc.layanans[key]?.jumlah || 0);
                            }
                        }
                    }
                    return total;
                }
            }));
        });
    </script>
</body>
</html>
