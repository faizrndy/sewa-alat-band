import 'package:flutter/material.dart';
import '../models/alat_band.dart';
import '../services/api_service.dart';
import 'auth_provider.dart';

/// Provider untuk mengelola state inventory/alat musik
/// Menangani CRUD operations, search, dan filter
class InventoryProvider with ChangeNotifier {
  // Dependency injection untuk authentication
  AuthProvider? _authProvider;

  // Private state variables
  List<AlatBand> _alatList = [];        // List semua alat musik
  bool _isLoading = false;              // Status loading
  String? _error;                       // Error message jika ada
  String _searchQuery = '';             // Query pencarian
  String? _selectedCategory;            // Kategori yang dipilih untuk filter

  // Constructor dengan dependency injection
  InventoryProvider(this._authProvider);

  /// Update auth provider reference (digunakan oleh ProxyProvider)
  void updateAuthProvider(AuthProvider authProvider) {
    _authProvider = authProvider;
  }

  /// Helper method untuk menangani error secara konsisten
  void _handleError(Object error) {
    // Extract error message yang lebih user-friendly
    String errorMessage = error.toString();
    
    // Hapus prefix "Exception: " jika ada
    if (errorMessage.startsWith('Exception: ')) {
      errorMessage = errorMessage.substring(11);
    }
    
    // Handle error messages yang lebih spesifik
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
    notifyListeners();
  }

  /// Helper method untuk set loading state
  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  // ==================== PUBLIC GETTERS ====================

  /// List alat musik yang sudah difilter berdasarkan search dan kategori
  List<AlatBand> get alatList => _filteredAlatList;

  /// Status loading untuk operasi async
  bool get isLoading => _isLoading;

  /// Error message jika ada kesalahan
  String? get error => _error;

  /// List kategori alat musik yang tersedia
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

    // Filter berdasarkan search query (nama alat atau kategori)
    if (_searchQuery.isNotEmpty) {
      final query = _searchQuery.toLowerCase();
      filtered = filtered.where((alat) =>
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
      final token = _authProvider?.token;
      if (token == null || token.isEmpty) {
        throw UnauthorizedException('Token tidak ditemukan. Silakan login kembali.');
      }
      final response = await ApiService.get('/api/admin/alat-band', token: token);
      final data = response is List ? response : response['data'] ?? [];
      _alatList = (data as List).map((json) => AlatBand.fromJson(json)).toList();
      _setLoading(false);
    } on UnauthorizedException catch (e) {
      // Auto logout on 401
      if (_authProvider != null) {
        await _authProvider!.logout();
      }
      _handleError(e);
      _alatList = [];
    } catch (e) {
      _handleError(e);
      _alatList = [];
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
