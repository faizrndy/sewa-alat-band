import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';

class AdminDrawer extends StatelessWidget {
  const AdminDrawer({super.key});

  @override
  Widget build(BuildContext context) {
    final authProvider = context.watch<AuthProvider>();

    return Drawer(
      child: ListView(
        padding: EdgeInsets.zero,
        children: [
          // Header
          UserAccountsDrawerHeader(
            accountName: Text(
              authProvider.user?.namaLengkap ?? authProvider.user?.name ?? 'Admin',
              style: const TextStyle(fontWeight: FontWeight.bold),
            ),
            accountEmail: Text(authProvider.user?.email ?? ''),
            currentAccountPicture: CircleAvatar(
              backgroundColor: Colors.blue,
              child: Text(
                (authProvider.user?.name ?? 'A')[0].toUpperCase(),
                style: const TextStyle(
                  fontSize: 24,
                  fontWeight: FontWeight.bold,
                  color: Colors.white,
                ),
              ),
            ),
            decoration: BoxDecoration(
              color: Colors.blue.shade700,
            ),
          ),

          // Menu Items
          ListTile(
            leading: const Icon(Icons.inventory, color: Colors.blue),
            title: const Text('Inventory Management'),
            subtitle: const Text('Kelola alat musik'),
            onTap: () {
              Navigator.pop(context); // Close drawer
              if (ModalRoute.of(context)?.settings.name != '/inventory') {
                Navigator.pushReplacementNamed(context, '/inventory');
              }
            },
          ),

          ListTile(
            leading: const Icon(Icons.add_box, color: Colors.green),
            title: const Text('Tambah Alat Musik'),
            subtitle: const Text('Tambah item baru'),
            onTap: () {
              Navigator.pop(context); // Close drawer
              Navigator.pushNamed(context, '/add-inventory');
            },
          ),

          const Divider(),

          ListTile(
            leading: const Icon(Icons.shopping_cart, color: Colors.orange),
            title: const Text('Katalog (Buyer Mode)'),
            subtitle: const Text('Lihat katalog sebagai buyer'),
            onTap: () {
              Navigator.pop(context); // Close drawer
              Navigator.pushNamed(context, '/catalog');
            },
          ),

          ListTile(
            leading: const Icon(Icons.history, color: Colors.purple),
            title: const Text('Riwayat Transaksi'),
            subtitle: const Text('Lihat transaksi saya'),
            onTap: () {
              Navigator.pop(context); // Close drawer
              Navigator.pushNamed(context, '/riwayat-transaksi');
            },
          ),

          const Divider(),

          ListTile(
            leading: const Icon(Icons.person, color: Colors.indigo),
            title: const Text('Profile'),
            subtitle: const Text('Kelola profile'),
            onTap: () {
              Navigator.pop(context); // Close drawer
              Navigator.pushNamed(context, '/profile');
            },
          ),

          ListTile(
            leading: const Icon(Icons.logout, color: Colors.red),
            title: const Text('Logout'),
            subtitle: const Text('Keluar dari aplikasi'),
            onTap: () async {
              Navigator.pop(context); // Close drawer

              // Show confirmation dialog
              final confirmed = await showDialog<bool>(
                context: context,
                builder: (context) => AlertDialog(
                  title: const Text('Konfirmasi Logout'),
                  content: const Text('Apakah Anda yakin ingin logout?'),
                  actions: [
                    TextButton(
                      onPressed: () => Navigator.pop(context, false),
                      child: const Text('Batal'),
                    ),
                    TextButton(
                      onPressed: () => Navigator.pop(context, true),
                      style: TextButton.styleFrom(foregroundColor: Colors.red),
                      child: const Text('Logout'),
                    ),
                  ],
                ),
              );

              if (confirmed == true && context.mounted) {
                await authProvider.logout();
                Navigator.pushReplacementNamed(context, '/login');
              }
            },
          ),
        ],
      ),
    );
  }
}

