<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kratak FC - Sewa Alat Band Terbaik</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        .gradient-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .gradient-overlay {
            background: linear-gradient(90deg, rgba(0,82,204,0.95) 0%, rgba(0,102,255,0.8) 100%);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,82,204,0.2);
        }

        .category-card {
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .category-card:hover {
            transform: scale(1.05);
            background: #E8F0FF;
        }

        .hero-section {
            background-image: url('https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=1920');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-blue-600 flex items-center">
                    <i class="fas fa-guitar mr-2"></i> Kratak FC
                </a>
                <div class="hidden md:flex space-x-8 items-center">
                    <a href="/" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
                    <a href="/katalog" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-box-open mr-1"></i> Katalog
                    </a>
                    <a href="/tentang" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-info-circle mr-1"></i> Tentang
                    </a>
                    <a href="/kontak" class="text-gray-700 hover:text-blue-600 transition font-medium">
                        <i class="fas fa-phone mr-1"></i> Kontak
                    </a>
                    <a href="/dashboard" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                    </a>
                </div>
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-gray-700">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
            <!-- Mobile Menu -->
            <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 space-y-2">
                <a href="/" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
                <a href="/katalog" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition">
                    <i class="fas fa-box-open mr-2"></i> Katalog
                </a>
                <a href="/tentang" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition">
                    <i class="fas fa-info-circle mr-2"></i> Tentang
                </a>
                <a href="/kontak" class="block px-4 py-2 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded transition">
                    <i class="fas fa-phone mr-2"></i> Kontak
                </a>
                <a href="/dashboard" class="block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
            </div>
        </div>
    </nav>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobile-menu-btn').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>

    <!-- Hero Section -->
    <section class="hero-section relative h-screen flex items-center justify-center">
        <div class="gradient-overlay absolute inset-0"></div>
        <div class="container mx-auto px-4 text-center relative z-10">
            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 animate-fade-in">
                Sewa Alat Band Terbaik
            </h1>
            <p class="text-xl md:text-2xl text-white mb-8 max-w-2xl mx-auto">
                Wujudkan impian bermusik Anda dengan koleksi alat band berkualitas profesional
            </p>
            <div class="flex flex-col md:flex-row gap-4 justify-center">
                <a href="/katalog" class="px-8 py-4 bg-white text-blue-600 rounded-lg font-semibold text-lg hover:bg-gray-100 transition shadow-lg">
                    <i class="fas fa-search mr-2"></i> Lihat Katalog
                </a>
                <a href="#featured" class="px-8 py-4 bg-transparent border-2 border-white text-white rounded-lg font-semibold text-lg hover:bg-white hover:text-blue-600 transition">
                    <i class="fas fa-star mr-2"></i> Produk Unggulan
                </a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Kategori Alat Band</h2>
                <p class="text-gray-600 text-lg">Temukan alat band sesuai kebutuhan Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @forelse($categories as $category)
                    <a href="/kategori/{{ $category }}" class="category-card bg-gray-50 rounded-xl p-6 text-center shadow-md">
                        <div class="w-20 h-20 mx-auto bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-drum text-blue-600 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $category }}</h3>
                    </a>
                @empty
                    <div class="col-span-4 text-center py-8">
                        <p class="text-gray-500">Belum ada kategori tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section id="featured" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Produk Unggulan</h2>
                <p class="text-gray-600 text-lg">Alat band pilihan dengan kualitas terbaik</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($featuredProducts as $product)
                    <div class="card-hover bg-white rounded-xl shadow-md overflow-hidden">
                        <!-- Product Image -->
                        <div class="h-56 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}"
                                     alt="{{ $product->nama_alat }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-guitar text-white text-6xl"></i>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="p-5">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full mb-3">
                                {{ $product->kategori }}
                            </span>

                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $product->nama_alat }}</h3>

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-2xl font-bold text-blue-600">
                                        Rp {{ number_format($product->harga_sewa, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500">per hari</p>
                                </div>
                                @if($product->status == 'Tersedia')
                                    <span class="text-green-600 text-sm font-semibold">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                @else
                                    <span class="text-red-600 text-sm font-semibold">
                                        <i class="fas fa-times-circle"></i> Disewa
                                    </span>
                                @endif
                            </div>

                            <a href="/product/{{ $product->id }}"
                               class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
                                <i class="fas fa-info-circle mr-2"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl text-gray-500">Belum ada produk unggulan</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="/katalog" class="inline-block px-8 py-4 bg-blue-600 text-white rounded-lg font-semibold text-lg hover:bg-blue-700 transition shadow-lg">
                    Lihat Semua Produk <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Latest Products -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Produk Terbaru</h2>
                <p class="text-gray-600 text-lg">Alat band yang baru saja tersedia</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($products as $product)
                    <div class="card-hover bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="h-56 bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center">
                            @if($product->gambar)
                                <img src="{{ asset('storage/' . $product->gambar) }}"
                                     alt="{{ $product->nama_alat }}"
                                     class="w-full h-full object-cover">
                            @else
                                <i class="fas fa-music text-white text-6xl"></i>
                            @endif
                        </div>

                        <div class="p-5">
                            <span class="inline-block px-3 py-1 text-xs font-semibold text-purple-600 bg-purple-100 rounded-full mb-3">
                                {{ $product->kategori }}
                            </span>

                            <h3 class="text-xl font-bold text-gray-800 mb-3">{{ $product->nama_alat }}</h3>

                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-2xl font-bold text-purple-600">
                                        Rp {{ number_format($product->harga_sewa, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500">per hari</p>
                                </div>
                                @if($product->status == 'Tersedia')
                                    <span class="text-green-600 text-sm font-semibold">
                                        <i class="fas fa-check-circle"></i> Tersedia
                                    </span>
                                @endif
                            </div>

                            <a href="/product/{{ $product->id }}"
                               class="block w-full text-center px-4 py-3 bg-purple-600 text-white rounded-lg font-semibold hover:bg-purple-700 transition">
                                <i class="fas fa-info-circle mr-2"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center py-12">
                        <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                        <p class="text-xl text-gray-500">Belum ada produk tersedia</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Why Choose Us -->
    <section class="py-16 gradient-blue">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-white mb-4">Mengapa Memilih Kami?</h2>
                <p class="text-white text-lg">Keunggulan layanan kami</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center text-white">
                    <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Kualitas Terjamin</h3>
                    <p class="text-white/90">Semua alat band dalam kondisi prima dan terawat dengan baik</p>
                </div>

                <div class="text-center text-white">
                    <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-dollar-sign text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Harga Terjangkau</h3>
                    <p class="text-white/90">Sistem sewa dengan harga yang kompetitif dan fleksibel</p>
                </div>

                <div class="text-center text-white">
                    <div class="w-20 h-20 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-headset text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3">Layanan 24/7</h3>
                    <p class="text-white/90">Customer service siap membantu kapan saja Anda membutuhkan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-guitar mr-2"></i> Kratak FC
                    </h3>
                    <p class="text-gray-400">Solusi terbaik untuk sewa alat band profesional dengan harga terjangkau.</p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Link Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="/" class="text-gray-400 hover:text-white transition">Beranda</a></li>
                        <li><a href="/katalog" class="text-gray-400 hover:text-white transition">Katalog</a></li>
                        <li><a href="/dashboard" class="text-gray-400 hover:text-white transition">Dashboard</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Kategori</h4>
                    <ul class="space-y-2">
                        @foreach($categories->take(4) as $category)
                            <li><a href="/kategori/{{ $category }}" class="text-gray-400 hover:text-white transition">{{ $category }}</a></li>
                        @endforeach
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-phone mr-2"></i> +62 123 4567 890</li>
                        <li><i class="fas fa-envelope mr-2"></i> info@kratakfc.com</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i> Surakarta, Indonesia</li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 pt-8 text-center">
                <p class="text-gray-400">&copy; 2024 Kratak FC - Sewa Alat Band Terbaik. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>