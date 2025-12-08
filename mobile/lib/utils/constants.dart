class Constants {
  // API Configuration
  static const String baseUrl = 'http://127.0.0.1:8000';
  static const String apiUrl = '$baseUrl/api';

  // API Endpoints
  static const String loginEndpoint = '/login';
  static const String registerEndpoint = '/register';
  static const String profileEndpoint = '/buyer/profile';
  static const String updateProfileEndpoint = '/buyer/update';
  static const String logoutEndpoint = '/buyer/logout';

  // Admin endpoints (if needed)
  static const String adminUsersEndpoint = '/admin/users';

  // SharedPreferences Keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';
  static const String isLoggedInKey = 'is_logged_in';

  // User Roles
  static const String roleUser = 'user';
  static const String roleAdmin = 'admin';

  // Validation Messages
  static const String requiredField = 'Field ini wajib diisi';
  static const String invalidEmail = 'Format email tidak valid';
  static const String passwordTooShort = 'Password minimal 8 karakter';
  static const String phoneInvalid = 'Format nomor telepon tidak valid';

  // App Info
  static const String appName = 'Sewa Alat Band - User Management';
  static const String appVersion = '1.0.0';
}
