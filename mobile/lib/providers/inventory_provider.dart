import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import '../models/alat_band.dart';
import '../services/api_service.dart';
import 'auth_provider.dart';

/// Provider untuk mengelola state inventory/alat musik
/// Menangani CRUD operations, search, filter, dan image upload
class InventoryProvider with ChangeNotifier {
  // Dependency injection untuk authentication
  AuthProvider? _authProvider;

  // Private state variables
  List<AlatBand> _alatList = [];        // List semua alat musik
  bool _isLoading = false;              // Status loading
  String? _error;                       // Error message jika ada
  String _searchQuery = '';             // Query pencarian
  String? _selectedCategory;            // Kategori yang dipilih untuk filter
  File? _selectedImage;                 // Gambar yang dipilih untuk upload

  // Constructor dengan dependency injection
  InventoryProvider(this._authProvider);

  /// Update auth provider reference (digunakan oleh ProxyProvider)
  void updateAuthProvider(AuthProvider authProvider) {
    _authProvider = authProvider;
  }

  /// Helper method untuk menangani error secara konsisten
  void _handleError(Object error) {
    _error = error.toString();
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

  /// Gambar yang sedang dipilih untuk upload
  File? get selectedImage => _selectedImage;

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

  /// Set gambar yang dipilih untuk upload dan notify listeners
  void setSelectedImage(File? image) {
    _selectedImage = image;
    notifyListeners();
  }

  /// Fetch list alat musik dari server
  /// Menggunakan endpoint admin dengan authentication token
  Future<void> fetchAlatList() async {
    _setLoading(true);
    _error = null;

    try {
      final token = _authProvider?.token;
      final response = await ApiService.get('/api/admin/alat-band', token: token);
      final data = response is List ? response : response['data'] ?? [];
      _alatList = (data as List).map((json) => AlatBand.fromJson(json)).toList();
    } catch (e) {
      _handleError(e);
      _alatList = [];
    }
  }

  /// Tambah alat musik baru ke database
  /// Mendukung upload gambar dan akan refresh list setelah berhasil
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

      if (_selectedImage != null) {
        await ApiService.postWithFile('/api/admin/alat-band', data, 'gambar', _selectedImage!, token: token);
      } else {
        await ApiService.post('/api/admin/alat-band', data, token: token);
      }

      await fetchAlatList();
      _selectedImage = null;
      return true;
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

      if (_selectedImage != null) {
        await ApiService.postWithFile('/api/admin/alat-band/$id', data, 'gambar', _selectedImage!, token: token);
      } else {
        await ApiService.post('/api/admin/alat-band/$id', data, token: token);
      }

      await fetchAlatList();
      _selectedImage = null;
      return true;
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
      await ApiService.delete('/api/admin/alat-band/$id', token: token);
      await fetchAlatList();
      return true;
    } catch (e) {
      _handleError(e);
      return false;
    }
  }

  /// Pilih gambar dari gallery untuk upload
  Future<void> pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      _selectedImage = File(pickedFile.path);
      notifyListeners();
    }
  }

  /// Clear error message
  void clearError() {
    _error = null;
    notifyListeners();
  }

  /// Clear gambar yang dipilih
  void clearSelectedImage() {
    _selectedImage = null;
    notifyListeners();
  }
}
