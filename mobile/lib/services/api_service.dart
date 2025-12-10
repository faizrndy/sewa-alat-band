import 'dart:convert';
import 'dart:io';
import 'dart:async';
import 'package:http/http.dart' as http;
import 'package:flutter/services.dart' show rootBundle;

class ApiService {
  static String? _baseUrl;
  static const Duration _timeout = Duration(seconds: 30);
  static const int _maxRetries = 3;

  static Future<String> get baseUrl async {
    if (_baseUrl == null) {
      try {
        final configString = await rootBundle.loadString('assets/config.json');
        final config = json.decode(configString);
        _baseUrl = config['api_url'] ?? 'http://127.0.0.1:8000';
      } catch (e) {
        _baseUrl = 'http://127.0.0.1:8000';
      }
    }
    return _baseUrl!;
  }

  static Map<String, String> get _headers {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
  }

  // Helper method for retry logic
  static Future<T> _retry<T>(
    Future<T> Function() operation,
    int maxRetries,
  ) async {
    int attempts = 0;
    while (attempts < maxRetries) {
      try {
        return await operation();
      } catch (e) {
        attempts++;
        if (attempts >= maxRetries) {
          throw e;
        }
        // Wait before retry (exponential backoff)
        await Future.delayed(Duration(seconds: attempts * 2));
      }
    }
    throw Exception('Max retries exceeded');
  }

  // GET request
  static Future<Map<String, dynamic>> get(String endpoint, {String? token}) async {
    return _retry(() async {
      try {
        final baseUrlValue = await baseUrl;
        final headers = {..._headers};
        if (token != null) {
          headers['Authorization'] = 'Bearer $token';
        }

        final response = await http.get(
          Uri.parse('$baseUrlValue$endpoint'),
          headers: headers,
        ).timeout(_timeout);

        return _handleResponse(response);
      } on TimeoutException {
        throw Exception('Connection timeout. Please check your internet connection.');
      } catch (e) {
        if (e is SocketException) {
          throw Exception('No internet connection. Please check your network.');
        }
        throw Exception('Network error: $e');
      }
    }, _maxRetries);
  }

  // POST request
  static Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data, {String? token}) async {
    return _retry(() async {
      try {
        final baseUrlValue = await baseUrl;
        final headers = {..._headers};
        if (token != null) {
          headers['Authorization'] = 'Bearer $token';
        }

        final response = await http.post(
          Uri.parse('$baseUrlValue$endpoint'),
          headers: headers,
          body: json.encode(data),
        ).timeout(_timeout);

        return _handleResponse(response);
      } on TimeoutException {
        throw Exception('Connection timeout. Please check your internet connection.');
      } catch (e) {
        if (e is SocketException) {
          throw Exception('No internet connection. Please check your network.');
        }
        throw Exception('Network error: $e');
      }
    }, _maxRetries);
  }

  // PUT request
  static Future<Map<String, dynamic>> put(String endpoint, Map<String, dynamic> data, {String? token}) async {
    try {
      final baseUrlValue = await baseUrl;
      final headers = {..._headers};
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }

      final response = await http.put(
        Uri.parse('$baseUrlValue$endpoint'),
        headers: headers,
        body: json.encode(data),
      );

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // DELETE request
  static Future<Map<String, dynamic>> delete(String endpoint, {String? token}) async {
    try {
      final baseUrlValue = await baseUrl;
      final headers = {..._headers};
      if (token != null) {
        headers['Authorization'] = 'Bearer $token';
      }

      final response = await http.delete(
        Uri.parse('$baseUrlValue$endpoint'),
        headers: headers,
      );

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  // POST with file upload (multipart/form-data)
  static Future<Map<String, dynamic>> postWithFile(
    String endpoint,
    Map<String, dynamic> data,
    String fileFieldName,
    File file, {
    String? token
  }) async {
    try {
      final baseUrlValue = await baseUrl;

      var request = http.MultipartRequest(
        'POST',
        Uri.parse('$baseUrlValue$endpoint'),
      );

      // Add token if provided
      if (token != null) {
        request.headers['Authorization'] = 'Bearer $token';
      }

      // Add text fields
      data.forEach((key, value) {
        if (value != null) {
          request.fields[key] = value.toString();
        }
      });

      // Add file
      if (file.existsSync()) {
        request.files.add(
          await http.MultipartFile.fromPath(
            fileFieldName,
            file.path,
          ),
        );
      }

      final streamedResponse = await request.send();
      final response = await http.Response.fromStream(streamedResponse);

      return _handleResponse(response);
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }

  static Map<String, dynamic> _handleResponse(http.Response response) {
    final statusCode = response.statusCode;
    final body = response.body;

    if (statusCode >= 200 && statusCode < 300) {
      if (body.isEmpty) {
        return {'success': true};
      }
      try {
        return json.decode(body);
      } catch (e) {
        throw Exception('Invalid JSON response: $body');
      }
    } else {
      // Try to parse error response
      try {
        final errorData = json.decode(body);
        final message = errorData['message'] ?? 'Request failed with status $statusCode';
        throw Exception(message);
      } catch (e) {
        throw Exception('Request failed with status $statusCode: $body');
      }
    }
  }
}

// Transaction API endpoints
class TransaksiApi {
  // Create new transaction
  static Future<Map<String, dynamic>> createTransaksi(
    String token, {
    required String nama,
    required String telepon,
    required String alamat,
    String? deskripsiLokasi,
    required double lat,
    required double lon,
    required double jarakKm,
    required String metodePengiriman,
    required int tarifAntar,
    required int totalSewa,
    required int totalBayar,
    required String identitas, // base64 string
    required List<Map<String, dynamic>> items,
  }) async {
    return ApiService.post('/api/transaksi', {
      'nama': nama,
      'telepon': telepon,
      'alamat': alamat,
      'deskripsi_lokasi': deskripsiLokasi,
      'lat': lat,
      'lon': lon,
      'jarak_km': jarakKm,
      'metode_pengiriman': metodePengiriman,
      'tarif_antar': tarifAntar,
      'total_sewa': totalSewa,
      'total_bayar': totalBayar,
      'identitas': identitas,
      'items': items,
    }, token: token);
  }

  // Get transaction history
  static Future<Map<String, dynamic>> getRiwayat(String token, String telepon) async {
    return ApiService.get('/api/riwayat/$telepon', token: token);
  }

  // Check availability
  static Future<Map<String, dynamic>> checkAvailability(
    int alatId,
    String tanggalMulai,
    String tanggalSelesai,
    int jumlahDiminta,
  ) async {
    return ApiService.post('/api/alat-band/check-availability', {
      'alat_id': alatId,
      'tanggal_mulai': tanggalMulai,
      'tanggal_selesai': tanggalSelesai,
      'jumlah_diminta': jumlahDiminta,
    });
  }
}

// Auth API endpoints
class AuthApi {
  // Register new user
  static Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String password,
    required String phone,
    String role = 'buyer',
  }) async {
    return ApiService.post('/api/register', {
      'nama_lengkap': name,
      'email': email,
      'password': password,
      'nomor_telepon': phone,
      'role': role,
    });
  }

  // Login user
  static Future<Map<String, dynamic>> login({
    required String email,
    required String password,
  }) async {
    return ApiService.post('/api/login', {
      'email': email,
      'password': password,
    });
  }

  // Get user profile
  static Future<Map<String, dynamic>> getProfile(String token) async {
    return ApiService.get('/api/buyer/profile', token: token);
  }

  // Update user profile
  static Future<Map<String, dynamic>> updateProfile(
    String token, {
    String? name,
    String? namaLengkap,
    String? phone,
  }) async {
    final data = <String, dynamic>{};
    if (name != null) data['name'] = name;
    if (namaLengkap != null) data['nama_lengkap'] = namaLengkap;
    if (phone != null) data['nomor_telepon'] = phone;

    return ApiService.put('/api/buyer/update', data, token: token);
  }

  // Logout user
  static Future<Map<String, dynamic>> logout(String token) async {
    return ApiService.post('/api/buyer/logout', {}, token: token);
  }
}

// Inventory API endpoints
class InventoryApi {
  // Get all alat band
  static Future<Map<String, dynamic>> getAlatBand({String? search, String? category}) async {
    String endpoint = '/api/alat-band';

    // Add query parameters if provided
    final queryParams = <String, String>{};
    if (search != null && search.isNotEmpty) {
      queryParams['search'] = search;
    }
    if (category != null && category.isNotEmpty) {
      queryParams['kategori'] = category;
    }

    if (queryParams.isNotEmpty) {
      final queryString = queryParams.entries.map((e) => '${e.key}=${e.value}').join('&');
      endpoint += '?$queryString';
    }

    return ApiService.get(endpoint);
  }

  // Create new alat band
  static Future<Map<String, dynamic>> createAlatBand(
    String token, {
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
    File? gambar,
  }) async {
    final data = {
      'nama_alat': namaAlat,
      'kategori': kategori,
      'stok': stok,
      'harga_sewa': hargaSewa,
      'status': status,
    };

    if (deskripsi != null && deskripsi.isNotEmpty) {
      data['deskripsi'] = deskripsi;
    }

    if (gambar != null) {
      return ApiService.postWithFile('/api/alat-band', data, 'gambar', gambar, token: token);
    } else {
      return ApiService.post('/api/alat-band', data, token: token);
    }
  }

  // Update alat band
  static Future<Map<String, dynamic>> updateAlatBand(
    String token,
    int id, {
    required String namaAlat,
    required String kategori,
    required int stok,
    required double hargaSewa,
    String? deskripsi,
    required String status,
    File? gambar,
  }) async {
    final data = {
      'nama_alat': namaAlat,
      'kategori': kategori,
      'stok': stok,
      'harga_sewa': hargaSewa,
      'status': status,
    };

    if (deskripsi != null && deskripsi.isNotEmpty) {
      data['deskripsi'] = deskripsi;
    }

    if (gambar != null) {
      return ApiService.postWithFile('/api/alat-band/$id', data, 'gambar', gambar, token: token);
    } else {
      return ApiService.post('/api/alat-band/$id', data, token: token);
    }
  }

  // Delete alat band
  static Future<Map<String, dynamic>> deleteAlatBand(String token, int id) async {
    return ApiService.delete('/api/alat-band/$id', token: token);
  }
}