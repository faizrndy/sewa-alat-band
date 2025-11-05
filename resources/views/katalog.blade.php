<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Sewa Alat Band</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold text-blue-600">
                    <i class="fas fa-guitar"></i> Kratak FC
                </a>
                <div class="hidden md:flex space-x-6">
                    <a href="/" class="text-gray-700 hover:text-blue-600">Beranda</a>
                    <a href="/katalog" class="text-blue-600 font-semibold">Katalog</a>
                    <a href="/dashboard" class="text-gray-700 hover:text-blue-600">Dashboard</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header -->
    <section class="bg-gradient-blue py-12">
        <div class="container mx-auto px-4 text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Katalog Alat Band</h1>
            <p class="text-lg">Temukan alat band terbaik untuk kebutuhan Anda</p>
        </div>
    </section>

    <!-- Filter & Search Section -->
    <section class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <form method="GET" action="{{ route('katalog') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Alat</label>
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Cari nama alat..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Filter Kategori -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="kategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('kategori') == $cat ? 'selected' : '' }}>
                                {{ $cat }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Disewa" {{ request('status') == 'Disewa' ? 'selected' : '' }}>Disewa</option>
                    </select>
                </div>

                <!-- Button -->
                <div class="md:col-span-4 flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-search"></i> Cari
                    </button>
                    <a href="{{ route('katalog') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">

                    <!-- Product Image -->
                    <div class="h-48 bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        @if($product->gambar)
                            <img src="{{ asset('storage/' . $product->gambar) }}"
                                 alt="{{ $product->nama_alat }}"
                                 class="w-full h-full object-cover">
                        @else
                            <i class="fas fa-drum text-white text-6xl"></i>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-4">
                        <!-- Kategori Badge -->
                        <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-600 bg-blue-100 rounded-full mb-2">
                            {{ $product->kategori }}
                        </span>

                        <!-- Nama Alat -->
                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $product->nama_alat }}</h3>

                        <!-- Harga -->
                        <p class="text-2xl font-bold text-blue-600 mb-2">
                            Rp {{ number_format($product->harga_sewa, 0, ',', '.') }}
                            <span class="text-sm text-gray-500">/hari</span>
                        </p>

                        <!-- Status -->
                        <div class="mb-3">
                            @if($product->status == 'Tersedia')
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-green-800 bg-green-100 rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i> Tersedia
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 text-sm font-medium text-red-800 bg-red-100 rounded-full">
                                    <i class="fas fa-times-circle mr-1"></i> Disewa
                                </span>
                            @endif
                        </div>

                        <!-- Button -->
                        <a href="{{ route('product.detail', $product->id) }}"
                           class="block w-full text-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-info-circle"></i> Detail
                        </a>
                    </div>
                </div>
            @empty
                <div class="md:col-span-4 text-center py-12">
                    <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
                    <p class="text-xl text-gray-500">Tidak ada produk ditemukan</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $products->links() }}
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; 2024 Kratak FC - Sewa Alat Band Terbaik</p>
        </div>
    </footer>

    <style>
        .bg-gradient-blue {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</body>
</html>