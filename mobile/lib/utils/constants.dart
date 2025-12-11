class Constants {
  // API Configuration
  static const String baseUrl = 'http://127.0.0.1:8000';

  // API Endpoints
  static const String loginEndpoint = '/login';
  static const String logoutEndpoint = '/buyer/logout';

  // SharedPreferences Keys
  static const String tokenKey = 'auth_token';
  static const String userKey = 'user_data';

  // User Roles
  static const String roleAdmin = 'admin';

  // Validation Messages
  static const String requiredField = 'Field ini wajib diisi';
  static const String invalidEmail = 'Format email tidak valid';
  static const String passwordTooShort = 'Password minimal 8 karakter';

  // App Info
  static const String appName = 'Sewa Alat Band - Admin';
  static const String appVersion = '1.0.0';

  // UI Constants
  static const double borderRadius = 12.0;
  static const double borderWidth = 2.0;
  static const double defaultPadding = 16.0;
  static const double buttonHeight = 50.0;
}
