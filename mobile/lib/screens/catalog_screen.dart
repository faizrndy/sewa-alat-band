import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/alat_band.dart';
import '../providers/auth_provider.dart';
import '../widgets/alat_card.dart';

class CatalogScreen extends StatefulWidget {
  const CatalogScreen({Key? key}) : super(key: key);

  @override
  State<CatalogScreen> createState() => _CatalogScreenState();
}

class _CatalogScreenState extends State<CatalogScreen> {
  final List<AlatBand> _alatList = [
    // Sample data - in real app this would come from API
    AlatBand(
      id: 1,
      namaAlat: 'Gitar Acoustic Yamaha',
      kategori: 'Gitar',
      stok: 5,
      hargaSewa: 50000,
      deskripsi: 'Gitar acoustic berkualitas tinggi dengan suara yang jernih',
      status: 'Tersedia',
    ),
    AlatBand(
      id: 2,
      namaAlat: 'Drum Set Pearl',
      kategori: 'Drum',
      stok: 2,
      hargaSewa: 150000,
      deskripsi: 'Drum set lengkap dengan semua komponen',
      status: 'Tersedia',
    ),
    AlatBand(
      id: 3,
      namaAlat: 'Keyboard Roland',
      kategori: 'Keyboard',
      stok: 3,
      hargaSewa: 100000,
      deskripsi: 'Keyboard digital dengan berbagai efek suara',
      status: 'Tersedia',
    ),
    AlatBand(
      id: 4,
      namaAlat: 'Bass Guitar Ibanez',
      kategori: 'Bass',
      stok: 4,
      hargaSewa: 75000,
      deskripsi: 'Bass guitar elektrik dengan pickup berkualitas',
      status: 'Tersedia',
    ),
  ];

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Sewa Alat Band'),
        backgroundColor: Colors.purple.shade700,
        foregroundColor: Colors.white,
      ),
      drawer: _buildDrawer(context),
      body: Column(
        children: [
          // Header
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [Colors.purple.shade700, Colors.purple.shade500],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                const Text(
                  'Selamat Datang!',
                  style: TextStyle(
                    color: Colors.white,
                    fontSize: 24,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 8),
                Consumer<AuthProvider>(
                  builder: (context, authProvider, child) {
                    if (authProvider.isAuthenticated) {
                      return Text(
                        'Halo, ${authProvider.user?.name ?? 'User'}!',
                        style: const TextStyle(
                          color: Colors.white70,
                          fontSize: 16,
                        ),
                      );
                    }
                    return const Text(
                      'Login untuk mulai menyewa alat musik',
                      style: TextStyle(
                        color: Colors.white70,
                        fontSize: 16,
                      ),
                    );
                  },
                ),
              ],
            ),
          ),

          // Content
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: _alatList.length,
              itemBuilder: (context, index) {
                final alat = _alatList[index];
                return AlatCard(
                  alat: alat,
                  onTap: () => _onAlatTap(alat),
                );
              },
            ),
          ),
        ],
      ),
      floatingActionButton: Consumer<AuthProvider>(
        builder: (context, authProvider, child) {
          if (!authProvider.isAuthenticated) {
            return FloatingActionButton.extended(
              onPressed: () => Navigator.pushNamed(context, '/login'),
              icon: const Icon(Icons.login),
              label: const Text('Login untuk Sewa'),
              backgroundColor: Colors.purple.shade700,
            );
          }
          return const SizedBox.shrink();
        },
      ),
    );
  }

  Widget _buildDrawer(BuildContext context) {
    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          DrawerHeader(
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [Colors.purple.shade700, Colors.purple.shade500],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Consumer<AuthProvider>(
              builder: (context, authProvider, child) {
                if (authProvider.isAuthenticated) {
                  return Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      const CircleAvatar(
                        radius: 30,
                        backgroundColor: Colors.white,
                        child: Icon(Icons.person, size: 40, color: Colors.purple),
                      ),
                      const SizedBox(height: 12),
                      Text(
                        authProvider.user?.name ?? 'User',
                        style: const TextStyle(
                          color: Colors.white,
                          fontSize: 18,
                          fontWeight: FontWeight.bold,
                        ),
                      ),
                      Text(
                        authProvider.user?.email ?? '',
                        style: const TextStyle(
                          color: Colors.white70,
                          fontSize: 14,
                        ),
                      ),
                    ],
                  );
                }
                return const Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    CircleAvatar(
                      radius: 30,
                      backgroundColor: Colors.white,
                      child: Icon(Icons.account_circle, size: 40, color: Colors.purple),
                    ),
                    SizedBox(height: 12),
                    Text(
                      'Sewa Alat Band',
                      style: TextStyle(
                        color: Colors.white,
                        fontSize: 20,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                    Text(
                      'Login untuk mulai menyewa',
                      style: TextStyle(
                        color: Colors.white70,
                        fontSize: 14,
                      ),
                    ),
                  ],
                );
              },
            ),
          ),

          Consumer<AuthProvider>(
            builder: (context, authProvider, child) {
              if (authProvider.isAuthenticated) {
                return Column(
                  children: [
                    ListTile(
                      leading: const Icon(Icons.music_note),
                      title: const Text('Katalog Alat'),
                      selected: true,
                      onTap: () {
                        Navigator.pop(context); // Close drawer
                      },
                    ),
                    ListTile(
                      leading: const Icon(Icons.history),
                      title: const Text('Riwayat Transaksi'),
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.pushNamed(context, '/riwayat-transaksi');
                      },
                    ),
                    ListTile(
                      leading: const Icon(Icons.person),
                      title: const Text('Profile'),
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.pushNamed(context, '/profile');
                      },
                    ),
                    const Divider(),
                    ListTile(
                      leading: const Icon(Icons.logout, color: Colors.red),
                      title: const Text('Logout', style: TextStyle(color: Colors.red)),
                      onTap: () {
                        Navigator.pop(context);
                        _showLogoutDialog(context);
                      },
                    ),
                  ],
                );
              } else {
                return Column(
                  children: [
                    ListTile(
                      leading: const Icon(Icons.music_note),
                      title: const Text('Katalog Alat'),
                      selected: true,
                      onTap: () {
                        Navigator.pop(context);
                      },
                    ),
                    const Divider(),
                    ListTile(
                      leading: const Icon(Icons.login),
                      title: const Text('Login'),
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.pushNamed(context, '/login');
                      },
                    ),
                    ListTile(
                      leading: const Icon(Icons.person_add),
                      title: const Text('Register'),
                      onTap: () {
                        Navigator.pop(context);
                        Navigator.pushNamed(context, '/register');
                      },
                    ),
                  ],
                );
              }
            },
          ),
        ],
      ),
    );
  }

  void _showLogoutDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (BuildContext context) {
        return AlertDialog(
          title: const Text('Konfirmasi Logout'),
          content: const Text('Apakah Anda yakin ingin keluar dari aplikasi?'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: Text(
                'Batal',
                style: TextStyle(color: Colors.grey.shade600),
              ),
            ),
            ElevatedButton(
              onPressed: () async {
                Navigator.pop(context); // Close dialog
                final authProvider = Provider.of<AuthProvider>(context, listen: false);
                await authProvider.logout();
                Navigator.pushReplacementNamed(context, '/login');
              },
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.red.shade600,
              ),
              child: const Text('Logout'),
            ),
          ],
        );
      },
    );
  }

  void _onAlatTap(AlatBand alat) {
    // For now, just show detail modal
    // In real app, you might want to navigate to detail screen
    // or directly to create transaction
    final authProvider = Provider.of<AuthProvider>(context, listen: false);

    if (!authProvider.isAuthenticated) {
      // Show login prompt
      showDialog(
        context: context,
        builder: (context) => AlertDialog(
          title: const Text('Login Diperlukan'),
          content: const Text('Silakan login terlebih dahulu untuk menyewa alat musik.'),
          actions: [
            TextButton(
              onPressed: () => Navigator.pop(context),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () {
                Navigator.pop(context);
                Navigator.pushNamed(context, '/login');
              },
              child: const Text('Login'),
            ),
          ],
        ),
      );
    } else {
      // Navigate to create transaction
      Navigator.pushNamed(
        context,
        '/create-transaksi',
        arguments: alat,
      );
    }
  }
}
