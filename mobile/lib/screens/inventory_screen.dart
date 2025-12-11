import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import '../providers/inventory_provider.dart';
import '../widgets/inventory_card.dart';
import 'add_inventory_screen.dart';
import 'edit_inventory_screen.dart';

class InventoryScreen extends StatefulWidget {
  const InventoryScreen({super.key});

  @override
  State<InventoryScreen> createState() => _InventoryScreenState();
}

class _InventoryScreenState extends State<InventoryScreen> {
  final TextEditingController _searchController = TextEditingController();
  String? _selectedCategory;

  @override
  void initState() {
    super.initState();
    _searchController.addListener(_onSearchChanged);
    WidgetsBinding.instance.addPostFrameCallback((_) {
      context.read<InventoryProvider>().fetchAlatList();
    });
  }

  void _onSearchChanged() {
    context.read<InventoryProvider>().setSearchQuery(_searchController.text);
  }

  @override
  void dispose() {
    _searchController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final inventoryProvider = context.watch<InventoryProvider>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Inventory Management'),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () => _navigateToAddScreen(context),
          ),
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => inventoryProvider.fetchAlatList(),
          ),
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () => _showLogoutConfirmation(context),
          ),
        ],
      ),
      body: Column(
        children: [
          // Search and Filter Section
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.grey[50],
            child: Column(
              children: [
                // Search Bar
                TextFormField(
                  controller: _searchController,
                  decoration: const InputDecoration(
                    labelText: 'Cari Alat Musik',
                    hintText: 'Cari nama alat atau kategori...',
                    prefixIcon: Icon(Icons.search),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.all(Radius.circular(12)),
                    ),
                  ),
                ),
                const SizedBox(height: 12),

                // Category Filter
                DropdownButtonFormField<String?>(
                  initialValue: _selectedCategory,
                  decoration: InputDecoration(
                    hintText: 'Filter berdasarkan kategori',
                    prefixIcon: const Icon(Icons.filter_list),
                    border: OutlineInputBorder(
                      borderRadius: BorderRadius.circular(8),
                    ),
                    contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                  ),
                  items: [
                    const DropdownMenuItem(
                      value: null,
                      child: Text('Semua Kategori'),
                    ),
                    ...inventoryProvider.categories.map((category) {
                      return DropdownMenuItem(
                        value: category,
                        child: Text(category),
                      );
                    }),
                  ],
                  onChanged: (value) {
                    setState(() {
                      _selectedCategory = value;
                    });
                    inventoryProvider.setSelectedCategory(value);
                  },
                ),
              ],
            ),
          ),

          // Content
          Expanded(
            child: inventoryProvider.isLoading
                ? const Center(child: CircularProgressIndicator())
                : inventoryProvider.error != null
                    ? _buildErrorState(inventoryProvider)
                    : _buildInventoryList(inventoryProvider),
          ),
        ],
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () => _navigateToAddScreen(context),
        backgroundColor: Colors.blue,
        child: const Icon(Icons.add, color: Colors.white),
      ),
    );
  }

  Widget _buildErrorState(InventoryProvider provider) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.error_outline, size: 64, color: Colors.red[300]),
          const SizedBox(height: 16),
          Text(
            'Error: ${provider.error}',
            textAlign: TextAlign.center,
            style: const TextStyle(color: Colors.red),
          ),
          const SizedBox(height: 16),
          SizedBox(
            width: double.infinity,
            height: 50,
            child: ElevatedButton(
              onPressed: () => provider.fetchAlatList(),
              style: ElevatedButton.styleFrom(
                backgroundColor: Colors.purple,
                shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(12),
                ),
              ),
              child: const Text(
                'Coba Lagi',
                style: TextStyle(
                  fontSize: 16,
                  fontWeight: FontWeight.w600,
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildInventoryList(InventoryProvider provider) {
    if (provider.alatList.isEmpty) {
      return Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(Icons.inventory_2_outlined, size: 64, color: Colors.grey),
            const SizedBox(height: 16),
            const Text(
              'Belum ada alat musik',
              style: TextStyle(fontSize: 18, color: Colors.grey),
            ),
            const SizedBox(height: 8),
            const Text(
              'Tap tombol + untuk menambah alat musik',
              style: TextStyle(color: Colors.grey),
            ),
          ],
        ),
      );
    }

    return RefreshIndicator(
      onRefresh: () async => provider.fetchAlatList(),
      child: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: provider.alatList.length,
        itemBuilder: (context, index) {
          final alat = provider.alatList[index];
          return InventoryCard(
            alat: alat,
            onEdit: () => _navigateToEditScreen(context, alat),
            onDelete: () => _showDeleteConfirmation(context, alat, provider),
          );
        },
      ),
    );
  }

  void _showLogoutConfirmation(BuildContext context) async {
    final authProvider = context.read<AuthProvider>();
    final navigator = Navigator.of(context);

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

    if (confirmed == true && mounted) {
      await authProvider.logout();
      navigator.pushReplacementNamed('/login');
    }
  }

  void _navigateToAddScreen(BuildContext context) {
    Navigator.push(
      context,
      MaterialPageRoute(builder: (context) => const AddInventoryScreen()),
    );
  }

  void _navigateToEditScreen(BuildContext context, dynamic alat) {
    Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => EditInventoryScreen(alat: alat),
      ),
    );
  }

  void _showDeleteConfirmation(BuildContext context, dynamic alat, InventoryProvider provider) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Hapus Alat Musik'),
        content: Text('Apakah Anda yakin ingin menghapus "${alat.namaAlat}"?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Batal'),
          ),
          TextButton(
            onPressed: () async {
              Navigator.pop(context);
              final scaffoldMessenger = ScaffoldMessenger.of(context);
              final success = await provider.deleteAlatBand(alat.id);
              if (success && mounted) {
                scaffoldMessenger.showSnackBar(
                  const SnackBar(content: Text('Alat musik berhasil dihapus')),
                );
              } else if (mounted) {
                scaffoldMessenger.showSnackBar(
                  SnackBar(content: Text('Error: ${provider.error}')),
                );
              }
            },
            style: TextButton.styleFrom(foregroundColor: Colors.red),
            child: const Text('Hapus'),
          ),
        ],
      ),
    );
  }
}
