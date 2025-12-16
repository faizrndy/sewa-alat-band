import 'dart:convert';
import 'dart:io';
import 'dart:async';
import 'package:http/http.dart' as http;

// Exception khusus buat error 401 Unauthorized

class UnauthorizedException implements Exception {
  final String message;
  UnauthorizedException(this.message);

  @override
  String toString() => message;
}

// Service buat handle semua HTTP requests ke backend
// Semua API calls lewat sini biar konsisten
class ApiService {
  // Base URL 
  static String? _baseUrl;

  // Timeout 30 detik
  static const Duration _timeout = Duration(seconds: 30);

  // Retry sampai 3 kali kalau gagal (network issues, dll)
  static const int _maxRetries = 3;

  // Getter lazy buat base URL
  static Future<String> get baseUrl async {
    _baseUrl ??= 'http://127.0.0.1:8000'; // Default localhost
    return _baseUrl!;
  }

  static Map<String, String> get _headers {
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    };
  }

  // Helper method buat retry logic
  // Kalau request gagal, coba lagi dengan delay yang makin lama
  static Future<T> _retry<T>(
    Future<T> Function() operation, // Function yang mau di-retry
    int maxRetries,
  ) async {
    int attempts = 0;

    while (attempts < maxRetries) {
      try {
        return await operation(); // Coba execute
      } catch (e) {
        attempts++;

        if (attempts >= maxRetries) {
          rethrow; // Udah gak ada kesempatan lagi, throw error
        }

        // Tunggu sebelum coba lagi (exponential backoff)
        await Future.delayed(Duration(seconds: attempts * 2));
      }
    }

    throw Exception('Max retries exceeded'); // Seharusnya gak pernah kesini
  }

  // GET request - buat ambil data dari server
  static Future<Map<String, dynamic>> get(String endpoint, {String? token}) async {
    return _retry(() async {
      try {
        final baseUrlValue = await baseUrl;

        // Setup headers
        final headers = {..._headers};
        if (token != null && token.isNotEmpty) {
          headers['Authorization'] = 'Bearer $token'; // Tambah token kalau ada
        }

        // Kirim GET request
        final response = await http.get(
          Uri.parse('$baseUrlValue$endpoint'),
          headers: headers,
        ).timeout(_timeout); // Timeout 30 detik

        return _handleResponse(response);
      } on UnauthorizedException {
        rethrow; // Biar caller handle khusus
      } on TimeoutException {
        throw Exception('Connection timeout. Please check your internet connection.');
      } catch (e) {
        if (e is SocketException) {
          throw Exception('No internet connection. Please check your network.');
        }
        if (e is UnauthorizedException) rethrow;
        throw Exception('Network error: $e');
      }
    }, _maxRetries);
  }

  // POST request - buat kirim data ke server (create/update)
  static Future<Map<String, dynamic>> post(String endpoint, Map<String, dynamic> data, {String? token}) async {
    return _retry(() async {
      try {
        final baseUrlValue = await baseUrl;

        // Setup headers
        final headers = {..._headers};
        if (token != null && token.isNotEmpty) {
          headers['Authorization'] = 'Bearer $token';
        }

        // Kirim POST request dengan data JSON
        final response = await http.post(
          Uri.parse('$baseUrlValue$endpoint'),
          headers: headers,
          body: json.encode(data), // Encode Map jadi JSON string
        ).timeout(_timeout);

        return _handleResponse(response);
      } on UnauthorizedException {
        rethrow;
      } on TimeoutException {
        throw Exception('Connection timeout. Please check your internet connection.');
      } catch (e) {
        if (e is SocketException) {
          throw Exception('No internet connection. Please check your network.');
        }
        if (e is UnauthorizedException) rethrow;
        throw Exception('Network error: $e');
      }
    }, _maxRetries);
  }

  // PUT request
  static Future<Map<String, dynamic>> put(String endpoint, Map<String, dynamic> data, {String? token}) async {
    try {
      final baseUrlValue = await baseUrl;
      final headers = {..._headers};
      if (token != null && token.isNotEmpty) {
        headers['Authorization'] = 'Bearer $token';
      }

      final response = await http.put(
        Uri.parse('$baseUrlValue$endpoint'),
        headers: headers,
        body: json.encode(data),
      );

      return _handleResponse(response);
    } on UnauthorizedException {
      rethrow;
    } catch (e) {
      if (e is UnauthorizedException) rethrow;
      throw Exception('Network error: $e');
    }
  }

  // DELETE request
  static Future<Map<String, dynamic>> delete(String endpoint, {String? token}) async {
    try {
      final baseUrlValue = await baseUrl;
      final headers = {..._headers};
      if (token != null && token.isNotEmpty) {
        headers['Authorization'] = 'Bearer $token';
      }

      final response = await http.delete(
        Uri.parse('$baseUrlValue$endpoint'),
        headers: headers,
      );

      return _handleResponse(response);
    } on UnauthorizedException {
      rethrow;
    } catch (e) {
      if (e is UnauthorizedException) rethrow;
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
    return _retry(() async {
      try {
        final baseUrlValue = await baseUrl;

        var request = http.MultipartRequest(
          'POST',
          Uri.parse('$baseUrlValue$endpoint'),
        );

        // Add headers
        request.headers.addAll({
          'Accept': 'application/json',
        });

        // Add token if provided
        if (token != null && token.isNotEmpty) {
          request.headers['Authorization'] = 'Bearer $token';
        }

        // Add text fields
        data.forEach((key, value) {
          if (value != null) {
            // Convert numbers to string properly
            if (value is int || value is double) {
              request.fields[key] = value.toString();
            } else {
              request.fields[key] = value.toString();
            }
          }
        });

        // Add file with error handling
        if (!file.existsSync()) {
          throw Exception('File tidak ditemukan: ${file.path}');
        }

        try {
          request.files.add(
            await http.MultipartFile.fromPath(
              fileFieldName,
              file.path,
            ),
          );
        } catch (e) {
          throw Exception('Gagal membaca file: $e');
        }

        final streamedResponse = await request.send().timeout(_timeout);
        final response = await http.Response.fromStream(streamedResponse);

        return _handleResponse(response);
      } on UnauthorizedException {
        rethrow;
      } on TimeoutException {
        throw Exception('Connection timeout. Request memakan waktu terlalu lama.');
      } catch (e) {
        if (e is SocketException) {
          throw Exception('No internet connection. Please check your network.');
        }
        if (e is UnauthorizedException) rethrow;
        throw Exception('Error: $e');
      }
    }, _maxRetries);
  }

  static Map<String, dynamic> _handleResponse(http.Response response) {
    final statusCode = response.statusCode;
    final body = response.body.trim();

    if (statusCode >= 200 && statusCode < 300) {
      if (body.isEmpty) return {'success': true};
      try {
        final decoded = json.decode(body);
        return decoded is List ? {'success': true, 'data': decoded} : decoded;
      } catch (e) {
        throw Exception('Invalid JSON: $body');
      }
    } else {
      // Handle 401 Unauthorized specifically
      if (statusCode == 401) {
        try {
          final errorData = json.decode(body);
          final message = errorData['message'] ?? 'Unauthorized: Token tidak valid atau telah kedaluwarsa';
          throw UnauthorizedException(message);
        } catch (e) {
          if (e is UnauthorizedException) rethrow;
          throw UnauthorizedException('Unauthorized: Silakan login kembali');
        }
      }
      
      // Handle other errors (422 validation errors, etc.)
      try {
        final errorData = json.decode(body);
        String message = errorData['message'] ?? errorData['error'] ?? 'Failed: $statusCode';
        
        // Handle validation errors (422)
        if (statusCode == 422 && errorData['errors'] != null) {
          final errors = errorData['errors'] as Map<String, dynamic>;
          final firstError = errors.values.first;
          if (firstError is List && firstError.isNotEmpty) {
            message = firstError.first.toString();
          }
        }
        
        throw Exception(message);
      } catch (e) {
        if (e is Exception) rethrow;
        throw Exception('Failed: $statusCode - $body');
      }
    }
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
    required String role,
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

  // Logout user
  static Future<Map<String, dynamic>> logout(String token) async {
    return ApiService.post('/api/buyer/logout', {}, token: token);
  }
}