import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../models/user.dart';
import '../services/api_service.dart';

class AuthProvider with ChangeNotifier {
  User? _user;
  String? _token;
  bool _isLoading = false;
  String? _error;
  bool _isProfileLoading = false; // Separate loading state for profile

  // Getters
  User? get user => _user;
  String? get token => _token;
  bool get isLoading => _isLoading;
  bool get isProfileLoading => _isProfileLoading;
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

  // Register new user
  Future<bool> register({
    required String name,
    required String email,
    required String password,
    required String phone,
  }) async {
    _setLoading(true);
    _error = null;

    try {
      final response = await AuthApi.register(
        name: name,
        email: email,
        password: password,
        phone: phone,
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

  // Get user profile - with duplicate call prevention
  Future<bool> fetchProfile({bool forceRefresh = false}) async {
    if (_token == null) {
      _error = 'No authentication token';
      return false;
    }

    // Prevent duplicate calls if already loading or if we have user data and not forcing refresh
    if (_isProfileLoading || (_user != null && !forceRefresh)) {
      return true; // Return true if we already have data
    }

    _isProfileLoading = true;
    _error = null;
    notifyListeners(); // Notify about loading state change

    try {
      final response = await AuthApi.getProfile(_token!);

      if (response['user'] != null) {
        _user = User.fromJson(response['user']);

        // Update stored user data
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user_data',
          'id:${_user!.id},name:${_user!.name},nama_lengkap:${_user!.namaLengkap},email:${_user!.email},nomor_telepon:${_user!.nomorTelepon},role:${_user!.role}'
        );

        _isProfileLoading = false;
        notifyListeners();
        return true;
      } else {
        throw Exception('Invalid response format');
      }
    } catch (e) {
      _error = e.toString();
      _isProfileLoading = false;
      notifyListeners();
      return false;
    }
  }

  // Update user profile
  Future<bool> updateProfile({
    String? name,
    String? namaLengkap,
    String? phone,
  }) async {
    if (_token == null) {
      _error = 'No authentication token';
      return false;
    }

    _setLoading(true);
    _error = null;

    try {
      final response = await AuthApi.updateProfile(
        _token!,
        name: name,
        namaLengkap: namaLengkap,
        phone: phone,
      );

      if (response['user'] != null) {
        _user = User.fromJson(response['user']);

        // Update stored user data
        final prefs = await SharedPreferences.getInstance();
        await prefs.setString('user_data',
          'id:${_user!.id},name:${_user!.name},nama_lengkap:${_user!.namaLengkap},email:${_user!.email},nomor_telepon:${_user!.nomorTelepon},role:${_user!.role}'
        );

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

    // Only fetch fresh profile if we have token but no user data
    if (_token != null && _user == null && !_isProfileLoading) {
      await fetchProfile();
    }

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

  // Refresh profile data manually
  Future<void> refreshProfile() async {
    await fetchProfile(forceRefresh: true);
  }

  // Clear error
  void clearError() {
    _error = null;
    notifyListeners();
  }
}
