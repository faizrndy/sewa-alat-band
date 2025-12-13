import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;
  bool _isLoading = false;
  String? _error;

  // Getters
  User? get user => _user;
  String? get token => _token;
  bool get isLoading => _isLoading;
  String? get error => _error;
  bool get isAuthenticated => _token != null && _user != null;

  AuthProvider(SharedPreferences prefs) {
    _loadStoredAuthData(prefs);
  }

  // Load stored authentication data
  void _loadStoredAuthData(SharedPreferences prefs) {
    _token = prefs.getString('auth_token');
    final userData = prefs.getString('user_data');
    if (userData != null) {
      try {
        final userMap = Map<String, dynamic>.from(
          userData.split(',').fold<Map<String, dynamic>>({}, (map, pair) {
            final parts = pair.split(':');
            if (parts.length == 2) {
              map[parts[0]] = parts[1];
            }
            return map;
          })
        );
        _user = User.fromJson(userMap);
      } catch (e) {
        // If parsing fails, clear stored data
        clearStoredData(prefs);
      }
    }
  }

  // Save authentication data
  Future<void> _saveAuthData(SharedPreferences prefs, String token, User user) async {
    await prefs.setString('auth_token', token);
    await prefs.setString('user_data',
      'id:${user.id},name:${user.name},nama_lengkap:${user.namaLengkap},email:${user.email},nomor_telepon:${user.nomorTelepon},role:${user.role}'
    );
  }

  // Clear stored authentication data
  Future<void> clearStoredData(SharedPreferences prefs) async {
    await prefs.remove('auth_token');
    await prefs.remove('user_data');
  }

  // Login user
  Future<bool> login({
    required String email,
    required String password,
  }) async {
    _setLoading(true);
    _error = null;

    try {
      final response = await AuthApi.login(
        email: email,
        password: password,
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
    if (_token == null) {
      // If no token, just clear local data
      await _clearAuthData();
      return true;
    }

    _setLoading(true);
    _error = null;

    try {
      await AuthApi.logout(_token!);
      await _clearAuthData();
      _setLoading(false);
      notifyListeners();
      return true;
    } catch (e) {
      // Even if logout API fails, clear local data
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
