import 'dart:io';
import 'package:flutter/material.dart';
import 'package:image_picker/image_picker.dart';
import '../models/alat_band.dart';
import '../services/api_service.dart';

class InventoryProvider with ChangeNotifier {
  List<AlatBand> _alatList = [];
  bool _isLoading = false;
  String? _error;
  String _searchQuery = '';
  String? _selectedCategory;
  File? _selectedImage;

  // Getters
  List<AlatBand> get alatList => _filteredAlatList;
  bool get isLoading => _isLoading;
  String? get error => _error;
  File? get selectedImage => _selectedImage;

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

  List<String> get statuses => [
    'Tersedia',
    'Disewa',
    'Dalam Perbaikan'
  ];

  // Filtered list berdasarkan search dan category
  List<AlatBand> get _filteredAlatList {
    List<AlatBand> filtered = _alatList;

    // Filter berdasarkan search query
    if (_searchQuery.isNotEmpty) {
      filtered = filtered.where((alat) =>
        alat.namaAlat.toLowerCase().contains(_searchQuery.toLowerCase()) ||
        alat.kategori.toLowerCase().contains(_searchQuery.toLowerCase())
      ).toList();
    }

    // Filter berdasarkan kategori
    if (_selectedCategory != null && _selectedCategory!.isNotEmpty) {
      filtered = filtered.where((alat) => alat.kategori == _selectedCategory).toList();
    }

    return filtered;
  }

  // Methods
  void setSearchQuery(String query) {
    _searchQuery = query;
    notifyListeners();
  }

  void setSelectedCategory(String? category) {
    _selectedCategory = category;
    notifyListeners();
  }

  void setSelectedImage(File? image) {
    _selectedImage = image;
    notifyListeners();
  }

  Future<void> fetchAlatList() async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await ApiService.get('/api/alat-band');
      if (response['success'] == true || response is List) {
        final data = response is List ? response : response['data'];
        _alatList = (data as List).map((json) => AlatBand.fromJson(json)).toList();
      } else {
        throw Exception('Failed to fetch data');
      }
    } catch (e) {
      _error = e.toString();
      _alatList = [];
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> addAlatBand({
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
  }) async {
    _isLoading = true;
    notifyListeners();

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

      // Jika ada gambar yang dipilih
      if (_selectedImage != null) {
        await ApiService.postWithFile(
          '/api/alat-band',
          data,
          'gambar',
          _selectedImage!
        );
      } else {
        await ApiService.post('/api/alat-band', data);
      }

      // Refresh list setelah add
      await fetchAlatList();
      _selectedImage = null;
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> updateAlatBand({
    required int id,
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
  }) async {
    _isLoading = true;
    notifyListeners();

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

      // Jika ada gambar baru yang dipilih
      if (_selectedImage != null) {
        await ApiService.postWithFile(
          '/api/alat-band/$id',
          data,
          'gambar',
          _selectedImage!
        );
      } else {
        await ApiService.post('/api/alat-band/$id', data);
      }

      // Refresh list setelah update
      await fetchAlatList();
      _selectedImage = null;
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  Future<bool> deleteAlatBand(int id) async {
    _isLoading = true;
    notifyListeners();

    try {
      await ApiService.delete('/api/alat-band/$id');
      // Refresh list setelah delete
      await fetchAlatList();
      return true;
    } catch (e) {
      _error = e.toString();
      return false;
    } finally {
      _isLoading = false;
      notifyListeners();
    }
  }

  // Pick image from gallery
  Future<void> pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      _selectedImage = File(pickedFile.path);
      notifyListeners();
    }
  }

  // Clear error
  void clearError() {
    _error = null;
    notifyListeners();
  }

  // Clear selected image
  void clearSelectedImage() {
    _selectedImage = null;
    notifyListeners();
  }
}
