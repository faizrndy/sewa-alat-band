import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/api_service.dart';

// Provider buat handle semua urusan autentikasi
// Login, register, logout, simpen token, dll
class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;
  bool _isLoading = false;
  String? _error;

  // Getter biar bisa diakses dari luar
  User? get user => _user;           // Data user yang lagi login
  String? get token => _token;       // Token autentikasi
  bool get isLoading => _isLoading;  // Status loading (nunggu API)
  String? get error => _error;       // Error message kalau ada

  // Helper getter buat cek udah login apa belum
  bool get isAuthenticated => _token != null && _user != null;

  // Constructor, langsung load data dari SharedPreferences
  AuthProvider(SharedPreferences prefs) {
    _loadStoredAuthData(prefs);
  }

  // Load data login yang disimpen dari sebelumnya
  void _loadStoredAuthData(SharedPreferences prefs) {
    // Ambil token yang disimpen
    _token = prefs.getString('auth_token');

    // Ambil data user (disimpen dalam format string yang kita parse)
    final userData = prefs.getString('user_data');
    if (userData != null) {
      try {
        // Parse string "id:1,name:John,email:john@example.com" jadi Map
        final userMap = Map<String, dynamic>.from(
          userData.split(',').fold<Map<String, dynamic>>({}, (map, pair) {
            final parts = pair.split(':');
            if (parts.length == 2) {
              map[parts[0]] = parts[1];
            }
            return map;
          })
        );

        // Convert jadi object User
        _user = User.fromJson(userMap);
      } catch (e) {
        // Kalau parsing gagal, bersihin data aja
        clearStoredData(prefs);
      }
    }
  }

  // Simpen data login ke SharedPreferences
  Future<void> _saveAuthData(SharedPreferences prefs, String token, User user) async {
    // Simpen token autentikasi
    await prefs.setString('auth_token', token);

    // Simpen data user dalam format string yang mudah di-parse
    await prefs.setString('user_data',
      'id:${user.id},name:${user.name},nama_lengkap:${user.namaLengkap},email:${user.email},nomor_telepon:${user.nomorTelepon},role:${user.role}'
    );
  }

  // Hapus data login dari penyimpanan (logout)
  Future<void> clearStoredData(SharedPreferences prefs) async {
    await prefs.remove('auth_token');
    await prefs.remove('user_data');
  }

  // Proses login user
  Future<bool> login({
    required String email,
    required String password,
  }) async {
    // Set loading state dan reset error
    _setLoading(true);
    _error = null;

    try {
      // Panggil API login
      final response = await AuthApi.login(
        email: email,
        password: password,
      );

      // Kalau response valid (ada user dan token)
      if (response['user'] != null && response['token'] != null) {
        // Simpen data user dan token
        _user = User.fromJson(response['user']);
        _token = response['token'];

        // Simpen ke SharedPreferences biar persistent
        final prefs = await SharedPreferences.getInstance();
        await _saveAuthData(prefs, _token!, _user!);

        // Update UI
        _setLoading(false);
        notifyListeners();

        return true; // Login berhasil
      } else {
        throw Exception('Response API gak valid');
      }
    } on UnauthorizedException catch (e) {
      _error = e.message;
      _setLoading(false);
      notifyListeners();
      return false;
    } catch (e) {
      // Kalau ada error, simpen message-nya
      _error = e.toString();
      _setLoading(false);
      notifyListeners();
      return false; // Login gagal
    }
  }

  // Register new user
  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String phone,
    String role = 'admin',
  }) async {
    _setLoading(true);
    _error = null;

    try {
      final response = await AuthApi.register(
        name: name,
        email: email,
        password: password,
        phone: phone,
        role: role,
      );

      if (response['user'] != null && response['token'] != null) {
        _user = User.fromJson(response['user']);
        _token = response['token'];

        // Save to SharedPreferences
        final prefs = await SharedPreferences.getInstance();
        await _saveAuthData(prefs, _token!, _user!);

        _setLoading(false);
        notifyListeners();
        return true;
      } else {
        throw Exception('Invalid response format');
      }
    } catch (e) {
      _error = e.toString();
      _setLoading(false);
      notifyListeners();
      return false;
    }
  }

  // Logout user
  Future<bool> logout() async {
    // Kalau gak ada token, langsung clear data aja
    if (_token == null) {
      await _clearAuthData();
      return true;
    }

    _setLoading(true);
    _error = null;

    try {
      // Panggil API logout dulu (biar server tahu)
      await AuthApi.logout(_token!);

      // Baru clear data local
      await _clearAuthData();

      _setLoading(false);
      notifyListeners();

      return true;
    } catch (e) {
      // Kalau API logout gagal, tetep clear data local aja
      // Biar user gak stuck
      await _clearAuthData();
      _setLoading(false);
      notifyListeners();
      return true;
    }
  }

  // Check authentication status on app start
  Future<void> checkAuthStatus() async {
    final prefs = await SharedPreferences.getInstance();
    _loadStoredAuthData(prefs);
    notifyListeners();
  }

  // Clear authentication data
  Future<void> _clearAuthData() async {
    final prefs = await SharedPreferences.getInstance();
    await clearStoredData(prefs);
    _user = null;
    _token = null;
  }

  // Helper method to set loading state
  void _setLoading(bool loading) {
    _isLoading = loading;
    notifyListeners();
  }

  // Clear error
  void clearError() {
    _error = null;
    notifyListeners();
  }
}
