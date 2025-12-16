import 'package:flutter/material.dart';
import '../models/alat_band.dart';
import '../services/api_service.dart';
import 'auth_provider.dart';

/// Provider buat handle semua data alat musik
/// CRUD, search, filter, dll. Semua logic bisnis ada di sini
class InventoryProvider with ChangeNotifier {
  // Butuh AuthProvider karena semua API butuh token
  AuthProvider? _authProvider;

  // State variables - data yang bisa berubah dan update UI
  List<AlatBand> _alatList = [];        // List semua alat dari server
  bool _isLoading = false;              // Nunggu API response
  String? _error;                       // Error message kalau ada masalah
  String _searchQuery = '';             // Kata kunci pencarian
  String? _selectedCategory;            // Filter kategori

  // Constructor - dependency injection biar bisa akses auth provider
  InventoryProvider(this._authProvider);

  /// Update referensi auth provider (dipanggil otomatis sama ProxyProvider)
  void updateAuthProvider(AuthProvider authProvider) {
    _authProvider = authProvider;
  }

  /// Handle error dengan cara yang konsisten
  /// Ubah error teknis jadi message yang user-friendly
  void _handleError(Object error) {
    String errorMessage = error.toString();

    // Bersihin prefix "Exception: " kalau ada
    if (errorMessage.startsWith('Exception: ')) {
      errorMessage = errorMessage.substring(11);
    }

    // Translate error teknis jadi bahasa manusia
    if (errorMessage.contains('Unauthorized') || errorMessage.contains('401')) {
      errorMessage = 'Sesi Anda telah berakhir. Silakan login kembali.';
    } else if (errorMessage.contains('422') || errorMessage.contains('validation')) {
      errorMessage = 'Data yang dimasukkan tidak valid. Periksa kembali form Anda.';
    } else if (errorMessage.contains('500') || errorMessage.contains('Internal Server Error')) {
      errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
    } else if (errorMessage.contains('Network') || errorMessage.contains('Connection')) {
      errorMessage = 'Tidak dapat terhubung ke server. Periksa koneksi internet Anda.';
    }

    _error = errorMessage;
    _isLoading = false;
    notifyListeners(); // Update UI biar nampil error
  }

  /// Helper method untuk set loading state
  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  // ==================== GETTERS BUAT DI AKSES DARI UI ====================

  /// List alat yang udah difilter sesuai search dan kategori
  /// Getter ini yang dipake di UI, bukan _alatList langsung
  List<AlatBand> get alatList => _filteredAlatList;

  /// Status loading - UI nungguin ini buat tampilin spinner
  bool get isLoading => _isLoading;

  /// Error message - kalau ada error, UI tampilin ini
  String? get error => _error;

  /// List kategori yang bisa dipilih user
  List<String> get categories => [
    'Gitar',
    'Bass',
    'Drum',
    'Keyboard',
    'Mikrofon',
    'Sound System',
    'Lighting',
    'Lainnya'
  ];

  // ==================== PRIVATE COMPUTED PROPERTIES ====================

  /// Computed property untuk list alat musik yang sudah difilter
  /// Menggabungkan filter berdasarkan search query dan kategori
  List<AlatBand> get _filteredAlatList {
    List<AlatBand> filtered = _alatList;

    // Filter berdasarkan kata kunci search
    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      filtered = filtered.where((alat) =>
        // Cari di nama alat atau kategori
        alat.namaAlat.toLowerCase().contains(query) ||
        alat.kategori.toLowerCase().contains(query)
      ).toList();
    }

    // Filter berdasarkan kategori yang dipilih
    if (_selectedCategory != null && _selectedCategory!.isNotEmpty) {
      filtered = filtered.where((alat) => alat.kategori == _selectedCategory).toList();
    }

    return filtered;
  }

  // ==================== PUBLIC METHODS ====================

  /// Set query pencarian dan notify listeners
  void setSearchQuery(String query) {
    _searchQuery = query;
    notifyListeners();
  }

  /// Set kategori yang dipilih untuk filter dan notify listeners
  void setSelectedCategory(String? category) {
    _selectedCategory = category;
    notifyListeners();
  }

  /// Fetch list alat musik dari server
  /// Menggunakan endpoint admin dengan authentication token
  Future<void> fetchAlatList() async {
    _setLoading(true);
    _error = null;

    try {
      // Pastikan ada token autentikasi
      final token = _authProvider?.token;
      if (token == null || token.isEmpty) {
        throw UnauthorizedException('Token tidak ditemukan. Silakan login kembali.');
      }

      // Panggil API untuk ambil data
      final response = await ApiService.get('/api/admin/alat-band', token: token);

      // Parse response jadi list of AlatBand
      final data = response is List ? response : response['data'] ?? [];
      _alatList = (data as List).map((json) => AlatBand.fromJson(json)).toList();

      _setLoading(false); // Sukses, matikan loading
    } on UnauthorizedException catch (e) {
      // Kalau token expired, auto logout
      if (_authProvider != null) {
        await _authProvider!.logout();
      }
      _handleError(e);
      _alatList = []; // Kosongin list
    } catch (e) {
      // Error lain (network, server, dll)
      _handleError(e);
      _alatList = []; // Kosongin list
    }
  }

  /// Tambah alat musik baru ke database
  /// Akan refresh list setelah berhasil
  Future<bool> addAlatBand({
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
  }) async {
    _setLoading(true);

    try {
      final Map<String, dynamic> data = {
        'nama_alat': namaAlat,
        'kategori': kategori,
        'stok': stok,
        'harga_sewa': hargaSewa,
        'status': status,
      };

      if (deskripsi != null && deskripsi.isNotEmpty) {
        data['deskripsi'] = deskripsi;
      }

      final token = _authProvider?.token;
      if (token == null || token.isEmpty) {
        throw UnauthorizedException('Token tidak ditemukan. Silakan login kembali.');
      }

      await ApiService.post('/api/admin/alat-band', data, token: token);
      await fetchAlatList();
      return true;
    } on UnauthorizedException catch (e) {
      // Auto logout on 401
      if (_authProvider != null) {
        await _authProvider!.logout();
      }
      _handleError(e);
      return false;
    } catch (e) {
      _handleError(e);
      return false;
    }
  }

  /// Update data alat musik yang sudah ada
  /// Sama seperti add tapi menggunakan endpoint dengan ID
  Future<bool> updateAlatBand({
    required int id,
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
  }) async {
    _setLoading(true);

    try {
      final Map<String, dynamic> data = {
        'nama_alat': namaAlat,
        'kategori': kategori,
        'stok': stok,
        'harga_sewa': hargaSewa,
        'status': status,
      };

      if (deskripsi != null && deskripsi.isNotEmpty) {
        data['deskripsi'] = deskripsi;
      }

      final token = _authProvider?.token;
      if (token == null || token.isEmpty) {
        throw UnauthorizedException('Token tidak ditemukan. Silakan login kembali.');
      }

      await ApiService.post('/api/admin/alat-band/$id', data, token: token);
      await fetchAlatList();
      return true;
    } on UnauthorizedException catch (e) {
      // Auto logout on 401
      if (_authProvider != null) {
        await _authProvider!.logout();
      }
      _handleError(e);
      return false;
    } catch (e) {
      _handleError(e);
      return false;
    }
  }

  /// Hapus alat musik berdasarkan ID
  /// Akan refresh list setelah berhasil dihapus
  Future<bool> deleteAlatBand(int id) async {
    _setLoading(true);

    try {
      final token = _authProvider?.token;
      if (token == null || token.isEmpty) {
        throw UnauthorizedException('Token tidak ditemukan. Silakan login kembali.');
      }
      await ApiService.delete('/api/admin/alat-band/$id', token: token);
      await fetchAlatList();
      return true;
    } on UnauthorizedException catch (e) {
      // Auto logout on 401
      if (_authProvider != null) {
        await _authProvider!.logout();
      }
      _handleError(e);
      return false;
    } catch (e) {
      _handleError(e);
      return false;
    }
  }

  /// Clear error message
  void clearError() {
    _error = null;
    notifyListeners();
  }
}
