<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Statistik Layanan Disdukcapil</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f8fafc;
        }
        .table-custom th {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 700;
        }
        .custom-scrollbar::-webkit-scrollbar {
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1; 
            border-radius: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8; 
        }
    </style>
</head>
<body class="bg-[#1e4e8c] min-h-screen text-gray-100 font-sans antialiased">

    <!-- Header -->
    <header class="bg-[#1a437a]/95 backdrop-blur-md sticky top-0 z-50 border-b border-white/10 shadow-[0_4px_20px_rgba(0,0,0,0.15)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo-tegal.png') }}" alt="Logo Kabupaten Tegal" class="w-10 h-auto drop-shadow-md">
                <div class="flex flex-col">
                    <h1 class="font-bold text-white leading-tight text-xl">Dinas <span class="text-blue-300">Dukcapil</span></h1>
                    <span class="text-base font-semibold text-blue-100 leading-tight">Kabupaten Tegal</span>
                </div>
            </div>
            <div class="hidden md:flex space-x-4 text-xs font-bold text-blue-300">
                <span>Tegal Luwih APIK</span>
                <span>Dukcapil PRIMA</span>
                <span>BerAKHLAK</span>
                <span>#BanggaMelayaniBangsa</span>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Title Section -->
        <div class="text-center mb-10">
            <h2 class="text-blue-300 font-bold text-sm tracking-widest uppercase mb-2">Pusat Data Layanan</h2>
            <h3 class="text-3xl md:text-4xl font-extrabold text-white mb-3">Pelayanan Dokumen Kependudukan<br>Dan Pencatatan Sipil</h3>
            <div class="flex items-center justify-center space-x-2 text-sm font-medium text-blue-200 mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span>Data hari ini - {{ $countKecamatan }} kecamatan + MPP + Dinas</span>
            </div>
        </div>

        <!-- 4 Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="text-xs text-gray-400 font-bold uppercase mb-1">Total {{ $countKecamatan }} kecamatan</div>
                <div class="text-4xl font-extrabold text-blue-600">{{ number_format($totalKecamatan, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="text-xs text-gray-400 font-bold uppercase mb-1">Total MPP</div>
                <div class="text-4xl font-extrabold text-orange-500">{{ number_format($totalMpp, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="text-xs text-gray-400 font-bold uppercase mb-1">Total Dinas Dukcapil</div>
                <div class="text-4xl font-extrabold text-teal-500">{{ number_format($totalDinas, 0, ',', '.') }}</div>
            </div>
            <div class="bg-white rounded-xl p-5 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="text-xs text-gray-400 font-bold uppercase mb-1">Total keseluruhan</div>
                <div class="text-4xl font-extrabold text-red-500">{{ number_format($totalKeseluruhan, 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Chart & Rekap Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
            
            <!-- Donut Chart -->
            <div class="lg:col-span-4 bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] flex flex-col">
                <div class="flex items-center space-x-2 mb-6">
                    <div class="w-1 h-5 bg-blue-500 rounded"></div>
                    <h4 class="font-bold text-base text-gray-700">Distribusi jenis layanan</h4>
                </div>
                <div class="relative flex-1 min-h-[250px] flex items-center justify-center">
                    <canvas id="donutChart"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                        <span class="text-3xl font-extrabold text-gray-800">{{ number_format($totalKeseluruhan, 0, ',', '.') }}</span>
                        <span class="text-[11px] text-gray-400 font-bold uppercase">Total layanan</span>
                    </div>
                </div>
                <!-- Custom Legend -->
                <div class="mt-6 flex flex-wrap justify-center gap-x-4 gap-y-2">
                    @foreach($chartLabels as $index => $label)
                        <div class="flex items-center space-x-1.5">
                            <span class="w-2 h-2 rounded-sm" style="background-color: {{ $chartColors[$index % count($chartColors)] }}"></span>
                            <span class="text-[9px] text-gray-500 font-bold uppercase">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Rekap Table -->
            <div class="lg:col-span-8 bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)]">
                <div class="flex items-center space-x-2 mb-1">
                    <div class="w-1 h-5 bg-blue-500 rounded"></div>
                    <h4 class="font-bold text-base text-gray-700">Rekap jenis layanan per titik</h4>
                </div>
                <p class="text-xs text-gray-400 mb-4 ml-3">Kecamatan = gabungan {{ $countKecamatan }} kecamatan</p>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-right table-custom">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="py-2 px-3 text-left w-1/3">Jenis Layanan</th>
                                <th class="py-2 px-3">Kecamatan</th>
                                <th class="py-2 px-3">MPP</th>
                                <th class="py-2 px-3">Dinas</th>
                                <th class="py-2 px-3">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm text-gray-600 font-semibold border-b border-gray-100">
                            @foreach($rekapData as $jenis => $data)
                            <tr class="border-b border-gray-50 hover:bg-gray-100 transition-colors cursor-default">
                                <td class="py-2.5 px-3 text-left text-gray-700 font-bold">{{ $jenis }}</td>
                                <td class="py-2.5 px-3">{{ number_format($data['kecamatan'], 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3">{{ number_format($data['mpp'], 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3">{{ number_format($data['dinas'], 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3 font-extrabold text-gray-800">{{ number_format($data['total'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-blue-50 text-blue-600 font-bold text-sm">
                                <td class="py-2.5 px-3 text-left rounded-l">Total</td>
                                <td class="py-2.5 px-3">{{ number_format($totalKecamatan, 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3">{{ number_format($totalMpp, 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3">{{ number_format($totalDinas, 0, ',', '.') }}</td>
                                <td class="py-2.5 px-3 rounded-r">{{ number_format($totalKeseluruhan, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rincian Lengkap per Titik -->
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] mb-8 overflow-hidden flex flex-col">
            <div class="flex items-center space-x-2 mb-1">
                <div class="w-1 h-5 bg-blue-500 rounded"></div>
                <h4 class="font-bold text-base text-gray-700">Rincian lengkap per titik layanan</h4>
            </div>
            <p class="text-xs text-gray-400 mb-4 ml-3">{{ $countKecamatan }} kecamatan + MPP + Dinas Dukcapil &mdash; geser ke samping untuk melihat semua kolom</p>
            
            <div class="overflow-x-auto overflow-y-auto max-h-[70vh] custom-scrollbar pb-2">
                <table class="w-full text-right table-custom whitespace-nowrap">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="py-3 px-4 text-left sticky top-0 left-0 bg-white z-20 shadow-[2px_2px_5px_-2px_rgba(0,0,0,0.05)] text-sm">{{ $countKecamatan }} Kecamatan + MPP + Dinas Dukcapil</th>
                            @foreach($layananTypes as $jenis)
                                <th class="py-3 px-4 text-xs sticky top-0 bg-white z-10 shadow-[0_2px_5px_-2px_rgba(0,0,0,0.05)]">{{ $jenis }}</th>
                            @endforeach
                            <th class="py-3 px-4 sticky top-0 right-0 bg-white z-20 shadow-[-2px_2px_5px_-2px_rgba(0,0,0,0.05)] text-sm">Total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-600 font-semibold border-b border-gray-100">
                        @foreach($rincianData as $loc => $data)
                            @php
                                $isSpecial = in_array($data['kategori'], ['mpp', 'dinas']);
                                $rowClass = $isSpecial ? 'bg-orange-50 text-orange-600 font-semibold group hover:bg-orange-100 transition-colors cursor-default' : 'border-b border-gray-50 group hover:bg-gray-100 transition-colors cursor-default';
                                $cellClass = $isSpecial ? 'bg-orange-50 group-hover:bg-orange-100 transition-colors' : 'bg-white group-hover:bg-gray-100 transition-colors';
                            @endphp
                            <tr class="{{ $rowClass }}">
                                <td class="py-3 px-4 text-left font-bold sticky left-0 {{ $cellClass }} shadow-[2px_0_5px_-2px_rgba(0,0,0,0.05)] {{ $isSpecial ? 'text-orange-600' : 'text-gray-700' }}">{{ $loc }}</td>
                                @foreach($layananTypes as $jenis)
                                    <td class="py-3 px-4">{{ number_format($data['layanans'][$jenis] ?? 0, 0, ',', '.') }}</td>
                                @endforeach
                                <td class="py-3 px-4 font-bold sticky right-0 {{ $cellClass }} shadow-[-2px_0_5px_-2px_rgba(0,0,0,0.05)]">{{ number_format($data['total'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Peringkat -->
        <div class="bg-white rounded-xl p-6 shadow-[0_2px_10px_-3px_rgba(6,81,237,0.1)] mb-12">
            <div class="flex items-center space-x-2 mb-4">
                <div class="w-1 h-5 bg-blue-500 rounded"></div>
                <h4 class="font-bold text-base text-gray-700">Peringkat seluruh titik layanan</h4>
            </div>
            <div class="flex items-center space-x-4 mb-6 text-xs font-bold text-gray-500">
                <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-blue-500"></span><span>Kecamatan</span></div>
                <div class="flex items-center space-x-1.5"><span class="w-2 h-2 rounded-full bg-orange-400"></span><span>MPP / Dinas</span></div>
            </div>

            <div class="space-y-1">
                @php
                    $maxTotal = count($rankingData) > 0 ? $rankingData[0]['total'] : 1;
                @endphp
                @foreach($rankingData as $rank)
                    @php
                        $isSpecial = in_array($rank['kategori'], ['mpp', 'dinas']);
                        $percent = ($rank['total'] / $maxTotal) * 100;
                    @endphp
                    <div class="flex items-center hover:bg-gray-100 px-3 py-2 -mx-3 rounded-xl transition-colors cursor-default">
                        <div class="w-32 text-sm font-semibold {{ $isSpecial ? 'text-orange-500 font-extrabold' : 'text-gray-700' }} truncate pr-2">
                            {{ $rank['nama'] }}
                        </div>
                        <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden relative">
                            <div class="absolute top-0 left-0 h-full rounded-full {{ $isSpecial ? 'bg-orange-400' : 'bg-blue-400' }}" style="width: {{ $percent }}%"></div>
                        </div>
                        <div class="w-16 text-right text-sm font-extrabold text-gray-600 pl-2">
                            {{ number_format($rank['total'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <footer class="flex flex-col items-center justify-center pb-8 border-t border-blue-800 pt-8 mt-8">
            <div class="border border-blue-400 text-blue-200 bg-blue-900/50 px-3 py-1.5 rounded-lg text-sm font-bold mb-6">
                {{ \Carbon\Carbon::parse($tanggalData)->locale('id')->translatedFormat('l, d F Y') }}
            </div>
            <div class="flex items-center space-x-6 text-xs font-bold text-blue-300 mb-4">
                <a href="https://www.tiktok.com/@disdukkotategal?is_from_webapp=1&sender_device=pc" target="_blank" class="hover:text-white transition-colors flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 448 512"><path d="M448 209.91a210.06 210.06 0 0 1-122.77-39.25V349.38A162.55 162.55 0 1 1 185 188.31V278.2a74.62 74.62 0 1 0 52.23 71.18V0l88 0a121.18 121.18 0 0 0 1.86 22.17h0A122.18 122.18 0 0 0 381 102.39a121.43 121.43 0 0 0 67 20.14Z"/></svg>
                    <span>@disdukkotategal</span>
                </a>
                <a href="https://www.facebook.com/dukcapilslawi/#" target="_blank" class="hover:text-white transition-colors flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 320 512"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>
                    <span>Dukcapil Kab. Tegal</span>
                </a>
                <a href="https://www.instagram.com/dukcapilslawi_ofc?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==" target="_blank" class="hover:text-white transition-colors flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>@dukcapilslawi_ofc</span>
                </a>
                <a href="https://disdukcapil.tegalkab.go.id" target="_blank" class="hover:text-white transition-colors flex items-center space-x-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                    <span>disdukcapil.tegalkab.go.id</span>
                </a>
            </div>
            <p class="text-xs text-blue-300 font-medium mt-2">Data diperbarui secara berkala oleh Admin Disdukcapil Kabupaten Tegal.</p>
        </footer>

    </main>

    <!-- Chart Configuration -->
    <script>
        const ctx = document.getElementById('donutChart').getContext('2d');
        
        const chartData = @json($chartData);
        const chartLabels = @json($chartLabels);
        const chartColors = @json($chartColors);
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: chartLabels,
                datasets: [{
                    data: chartData,
                    backgroundColor: chartColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false // We use custom HTML legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1e293b',
                        bodyColor: '#475569',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        padding: 10,
                        boxPadding: 4,
                        usePointStyle: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += new Intl.NumberFormat('id-ID').format(context.parsed);
                                }
                                return label;
                            }
                        }
                    }
                },
                layout: {
                    padding: 10
                }
            }
        });
    </script>
</body>
</html>
