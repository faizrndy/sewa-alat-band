# Sewa Alat Band Mobile App

Aplikasi Flutter untuk CRUD (Create, Read, Update, Delete) user yang terintegrasi dengan backend Laravel API.

## 📱 Fitur

- ✅ **Register**: Pendaftaran user baru
- ✅ **Login**: Autentikasi user
- ✅ **Profile**: Melihat informasi profile user
- ✅ **Edit Profile**: Mengupdate data profile user
- ✅ **Logout**: Keluar dari aplikasi
- ✅ **Persistent Login**: Menyimpan session login
- ✅ **Error Handling**: Penanganan error yang user-friendly

## 🏗️ Struktur Project

```
lib/
├── models/
│   └── user.dart                    # Model data User
├── providers/
│   └── auth_provider.dart          # State management untuk authentication
├── services/
│   ├── api_service.dart            # Service untuk API calls
│   └── auth_service.dart           # Helper untuk auth endpoints
├── screens/
│   ├── login_screen.dart           # Screen login
│   ├── register_screen.dart        # Screen register
│   ├── profile_screen.dart         # Screen profile
│   └── edit_profile_screen.dart    # Screen edit profile
├── widgets/
│   ├── custom_button.dart          # Custom button widget
│   └── custom_text_field.dart      # Custom text field widget
└── main.dart                       # Entry point aplikasi
```

## 🚀 Setup & Installation

### 1. Prerequisites

- Flutter SDK (>= 3.0.0)
- Android Studio / VS Code
- Android SDK / iOS Simulator
- Backend Laravel API (pastikan sudah running)

### 2. Clone & Setup

```bash
# Install dependencies
flutter pub get

# Setup untuk Android (opsional)
flutter config --enable-android

# Setup untuk iOS (opsional)
flutter config --enable-ios
```

### 3. Konfigurasi API

Edit file `assets/config.json`:

```json
{
  "api_url": "http://127.0.0.1:8000",
  "app_name": "Sewa Alat Band Mobile",
  "app_version": "1.0.0",
  "debug": true
}
```

### 4. Jalankan Aplikasi

#### **🎯 Dengan Port Tetap (Recommended)**

Untuk menghindari masalah CORS setiap berganti port, gunakan port tetap:

**Windows:**
```batch
# Double-click file ini atau jalankan di command prompt:
run_dev.bat
```

**Linux/Mac:**
```bash
# Jalankan script dengan port tetap:
./run_dev.sh

# Atau manual:
flutter run --web-port=8080
```

**Manual Command:**
```bash
# Development mode dengan port tetap
flutter run --web-port=8080

# Build APK
flutter build apk --release

# Build iOS (hanya di macOS)
flutter build ios --release
```

> **⚠️ Penting:** Selalu gunakan `--web-port=8080` untuk konsistensi port dan menghindari CORS issues!

## 🔧 API Integration

Aplikasi ini menggunakan Laravel Sanctum untuk authentication. Pastikan backend Laravel sudah dikonfigurasi dengan benar.

### Endpoints yang digunakan:

- `POST /api/register` - Register user baru
- `POST /api/login` - Login user
- `GET /api/buyer/profile` - Get profile user
- `PUT /api/buyer/update` - Update profile user
- `POST /api/buyer/logout` - Logout user

### Headers yang dikirim:

```dart
{
  'Content-Type': 'application/json',
  'Accept': 'application/json',
  'Authorization': 'Bearer {token}' // untuk request yang butuh auth
}
```

## 📱 Fitur CRUD User

### 1. **Create (Register)**
- Form validasi input
- Check password confirmation
- Error handling untuk email sudah terdaftar

### 2. **Read (Profile)**
- Tampilan data user yang lengkap
- Auto-refresh data saat screen dibuka
- Loading state dan error handling

### 3. **Update (Edit Profile)**
- Form pre-filled dengan data existing
- Validasi input
- Real-time feedback untuk user

### 4. **Authentication Management**
- Persistent login menggunakan SharedPreferences
- Auto-check authentication status
- Secure token storage

## 🎨 UI/UX Features

- **Material Design 3**: Modern UI dengan Material Design
- **Responsive**: Cocok untuk berbagai ukuran layar
- **Loading States**: Feedback visual saat loading
- **Error Messages**: Pesan error yang jelas dan helpful
- **Form Validation**: Validasi real-time pada form input
- **Smooth Navigation**: Transisi yang smooth antar screen

## 🔒 Security Features

- **Token-based Authentication**: Menggunakan JWT token
- **Secure Storage**: Token disimpan dengan aman
- **Input Validation**: Validasi di client dan server side
- **Error Sanitization**: Error message yang aman

## 🐛 Troubleshooting

### Connection Refused
```
Error: Connection refused
```
**Solusi:**
- Pastikan backend Laravel sudah running di `http://127.0.0.1:8000`
- Check firewall/antivirus
- Verify IP address di config

### CORS Error
```
XMLHttpRequest error
```
**Solusi:**
- Pastikan CORS sudah dikonfigurasi di Laravel
- Check `config/cors.php` di backend
- **Pastikan Flutter menggunakan port 8080**: `flutter run --web-port=8080`

### Port Berubah Terus (Random Port Issue)
```
Flutter development server keeps changing ports!
```
**Penyebab:** Flutter otomatis assign port yang tersedia

**Solusi:**
```bash
# ✅ SELALU gunakan port tetap
flutter run --web-port=8080

# Atau gunakan script:
# Windows: double-click run_dev.bat
# Linux/Mac: ./run_dev.sh
```

**Konfigurasi Port Tetap:**
- Port 8080 sudah ditambahkan ke CORS allowed origins
- Gunakan flag `--web-port=8080` untuk konsistensi
- Jangan edit pubspec.yaml untuk port (akan error)

### Pubspec.yaml Error
```
Unexpected child "web" found under "flutter"
```
**Penyebab:** Konfigurasi web port di pubspec.yaml tidak didukung

**Solusi:**
- ❌ Jangan tambahkan `web: port: 8080` di pubspec.yaml
- ✅ Gunakan `flutter run --web-port=8080` di command line
- ✅ Atau gunakan script `run_dev.bat` / `run_dev.sh`

**File pubspec.yaml yang benar:**
```yaml
flutter:
  uses-material-design: true
  # ❌ Jangan tambahkan web/port di sini
```

### Authentication Failed
```
Unauthorized
```
**Solusi:**
- Check token validity
- Verify API endpoints
- Check Laravel Sanctum configuration

## 📋 Dependencies Used

```yaml
dependencies:
  flutter:
    sdk: flutter

  # HTTP requests
  http: ^1.2.0

  # State management
  provider: ^6.1.1

  # Local storage
  shared_preferences: ^2.2.2

  # Loading overlay
  loading_overlay: ^0.3.0

  # Toast notifications
  fluttertoast: ^8.2.4

  # Date formatting
  intl: ^0.19.0
```

## 🎯 Testing

```bash
# Run tests
flutter test

# Run integration tests
flutter test integration_test/

# Check code quality
flutter analyze

# Format code
flutter format lib/
```

## 📄 License

This project is licensed under the MIT License.

---

**Happy Coding! 🚀**

Dibuat dengan ❤️ untuk aplikasi Sewa Alat Band