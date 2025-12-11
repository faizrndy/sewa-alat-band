import 'dart:convert';
import 'dart:io';
import 'dart:async';
import 'package:http/http.dart' as http;

class ApiService {
  static String? _baseUrl;
  static const Duration _timeout = Duration(seconds: 30);
  static const int _maxRetries = 3;

  static Future<String> get baseUrl async {
    _baseUrl ??= 'http://127.0.0.1:8000';
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
          rethrow;
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
      try {
        final errorData = json.decode(body);
        final message = errorData['message'] ?? errorData['error'] ?? 'Failed: $statusCode';
        throw Exception(message);
      } catch (e) {
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