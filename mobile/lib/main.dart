import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';

import 'providers/auth_provider.dart';
import 'providers/inventory_provider.dart';
import 'screens/login_screen.dart';
import 'screens/register_screen.dart';
import 'screens/inventory_screen.dart';
import 'screens/add_inventory_screen.dart';
import 'screens/edit_inventory_screen.dart';
import 'utils/constants.dart';

// Nah ini entry point aplikasi kita
// Flutter butuh ini biar bisa akses native stuff kayak SharedPreferences
void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Matikan debug check biar gak ribet development
  Provider.debugCheckInvalidValueType = null;

  // Ambil SharedPreferences untuk nyimpen data login
  final prefs = await SharedPreferences.getInstance();

  // Jalankan app dengan data preferences
  runApp(MyApp(prefs: prefs));
}

class MyApp extends StatelessWidget {
  final SharedPreferences prefs;

  const MyApp({super.key, required this.prefs});

  @override
  Widget build(BuildContext context) {
    return MultiProvider(
      // Setup semua state management provider di sini
      // Kayak dependency injection tapi untuk UI state
      providers: [
        // Provider untuk handle autentikasi user
        // Bawa SharedPreferences biar bisa nyimpen/ambil token
        ChangeNotifierProvider(
          create: (_) => AuthProvider(prefs),
        ),

        // Provider untuk handle data alat musik
        // Depend on AuthProvider karena butuh token untuk API calls
        ChangeNotifierProxyProvider<AuthProvider, InventoryProvider>(
          create: (_) => InventoryProvider(null), // Buat dulu tanpa auth
          update: (_, authProvider, previous) {
            // Kalau udah ada instance sebelumnya, tinggal update auth-nya
            // Biar gak bikin instance baru terus menerus
            if (previous != null) {
              previous.updateAuthProvider(authProvider);
              return previous; // Pakai yang lama, tinggal update auth
            }
            // Kalau belum ada, bikin baru dengan auth provider
            return InventoryProvider(authProvider);
          },
        ),
      ],
      child: MaterialApp(
        title: 'Sewa Alat Band - Admin',
        theme: ThemeData(
          primarySwatch: Colors.purple,
          scaffoldBackgroundColor: Colors.grey.shade50,
          appBarTheme: AppBarTheme(
            backgroundColor: Colors.purple.shade700,
            foregroundColor: Colors.white,
            elevation: 0,
            centerTitle: true,
            shape: RoundedRectangleBorder(
              borderRadius: const BorderRadius.vertical(
                bottom: Radius.circular(20),
              ),
            ),
          ),
          elevatedButtonTheme: ElevatedButtonThemeData(
            style: ElevatedButton.styleFrom(
              backgroundColor: Colors.purple.shade700,
              foregroundColor: Colors.white,
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
              shape: RoundedRectangleBorder(
                borderRadius: BorderRadius.circular(12),
              ),
              elevation: 2,
            ),
          ),
          inputDecorationTheme: InputDecorationTheme(
            border: OutlineInputBorder(
              borderRadius: BorderRadius.circular(Constants.borderRadius),
            ),
            enabledBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(Constants.borderRadius),
              borderSide: BorderSide(color: Colors.grey.shade300),
            ),
            focusedBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(Constants.borderRadius),
              borderSide: BorderSide(color: Colors.purple.shade400, width: Constants.borderWidth),
            ),
            errorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(Constants.borderRadius),
              borderSide: BorderSide(color: Colors.red.shade400),
            ),
            focusedErrorBorder: OutlineInputBorder(
              borderRadius: BorderRadius.circular(Constants.borderRadius),
              borderSide: BorderSide(color: Colors.red.shade400, width: Constants.borderWidth),
            ),
            filled: true,
            fillColor: Colors.white,
            contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
            labelStyle: TextStyle(color: Colors.grey.shade700),
            hintStyle: TextStyle(color: Colors.grey.shade400),
          ),
          textTheme: const TextTheme(
            headlineSmall: TextStyle(
              fontWeight: FontWeight.bold,
              color: Colors.black87,
            ),
            bodyLarge: TextStyle(
              color: Colors.black87,
              height: 1.5,
            ),
          ),
        ),
        // Screen pertama yang muncul, nanti dia yang handle login check
        home: const AuthWrapper(),

        // Setup routing untuk navigasi antar halaman
        routes: {
          '/login': (context) => const LoginScreen(),
          '/register': (context) => const RegisterScreen(),
          '/inventory': (context) => const InventoryScreen(), // List semua alat
          '/add-inventory': (context) => const AddInventoryScreen(), // Tambah alat baru
          '/edit-inventory': (context) {
            // Ambil data alat yang mau diedit dari arguments
            // Biasanya dikirim dari halaman list saat user tap edit
            final alat = ModalRoute.of(context)!.settings.arguments as dynamic;
            return EditInventoryScreen(alat: alat);
          },
        },
        debugShowCheckedModeBanner: true,
      ),
    );
  }
}

// Widget ini yang handle cek login pertama kali
// User buka app → cek udah login apa belum → redirect ke screen yang sesuai
class AuthWrapper extends StatefulWidget {
  const AuthWrapper({super.key});

  @override
  State<AuthWrapper> createState() => _AuthWrapperState();
}

class _AuthWrapperState extends State<AuthWrapper> {
  // Flag biar gak ngecek auth berkali-kali
  bool _hasCheckedAuth = false;

  @override
  void initState() {
    super.initState();
    _checkAuth();
  }

  // Cek status login dari SharedPreferences
  Future<void> _checkAuth() async {
    // Kalau udah pernah cek, skip aja
    if (_hasCheckedAuth) return;

    _hasCheckedAuth = true;

    // Ambil auth provider tanpa listen (karena ini cuma sekali)
    final authProvider = Provider.of<AuthProvider>(context, listen: false);

    // Cek ada token yang valid apa gak
    await authProvider.checkAuthStatus();
  }

  @override
  Widget build(BuildContext context) {
    // Listen ke AuthProvider biar tahu kapan status login berubah
    return Consumer<AuthProvider>(
      builder: (context, authProvider, child) {
        // Selama lagi loading atau belum selesai cek auth, tampilin loading
        if (!_hasCheckedAuth || authProvider.isLoading) {
          return Scaffold(
            body: Container(
              // Background gradient biar cantik
              decoration: BoxDecoration(
                gradient: LinearGradient(
                  begin: Alignment.topCenter,
                  end: Alignment.bottomCenter,
                  colors: [
                    Colors.purple.shade700,
                    Colors.purple.shade500,
                  ],
                ),
              ),
              child: const Center(
                child: Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    CircularProgressIndicator(
                      color: Colors.white,
                      strokeWidth: 3,
                    ),
                    SizedBox(height: 24),
                    Text(
                      'Memuat...',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 16,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ],
                ),
              ),
            ),
          );
        }

        // Kalau udah login, langsung ke halaman inventory
        if (authProvider.isAuthenticated) {
          return const InventoryScreen();
        }

        // Kalau belum login, ke halaman login
        return const LoginScreen();
      },
    );
  }
}