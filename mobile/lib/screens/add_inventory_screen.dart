import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/inventory_provider.dart';
import '../providers/auth_provider.dart';
import '../widgets/custom_button.dart';
import '../widgets/custom_text_field.dart';

class AddInventoryScreen extends StatefulWidget {
  const AddInventoryScreen({super.key});

  @override
  State<AddInventoryScreen> createState() => _AddInventoryScreenState();
}

class _AddInventoryScreenState extends State<AddInventoryScreen> {
  final _formKey = GlobalKey<FormState>();
  final _namaController = TextEditingController();
  final _stokController = TextEditingController();
  final _hargaController = TextEditingController();
  final _deskripsiController = TextEditingController();

  String _selectedKategori = 'Gitar';
  String _selectedStatus = 'Tersedia';
  bool _isLoading = false;

  final List<String> _kategoriList = [
    'Gitar',
    'Bass',
    'Drum',
    'Keyboard',
    'Mikrofon',
    'Sound System',
    'Lighting',
    'Lainnya'
  ];

  final List<String> _statusList = [
    'Tersedia',
    'Disewa',
    'Dalam Perbaikan'
  ];

  @override
  void dispose() {
    _namaController.dispose();
    _stokController.dispose();
    _hargaController.dispose();
    _deskripsiController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final inventoryProvider = context.watch<InventoryProvider>();

    return Scaffold(
      appBar: AppBar(
        title: const Text('Tambah Alat Musik'),
        backgroundColor: Colors.blue,
        foregroundColor: Colors.white,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    // Image Preview
                    _buildImagePreview(inventoryProvider),
                    const SizedBox(height: 24),

                    // Form Fields
                    CustomTextField(
                      controller: _namaController,
                      labelText: 'Nama Alat Musik',
                      hintText: 'Contoh: Yamaha Pacifica 612VII',
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Nama alat musik tidak boleh kosong';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Kategori Dropdown
                    DropdownButtonFormField<String>(
                      value: _selectedKategori,
                      decoration: InputDecoration(
                        labelText: 'Kategori',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      ),
                      items: _kategoriList.map((kategori) {
                        return DropdownMenuItem(
                          value: kategori,
                          child: Text(kategori),
                        );
                      }).toList(),
                      onChanged: (value) {
                        setState(() {
                          _selectedKategori = value!;
                        });
                      },
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Kategori harus dipilih';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Stok Field
                    CustomTextField(
                      controller: _stokController,
                      labelText: 'Stok',
                      hintText: '0',
                      keyboardType: TextInputType.number,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Stok tidak boleh kosong';
                        }
                        final stok = int.tryParse(value);
                        if (stok == null || stok < 0) {
                          return 'Stok harus berupa angka positif';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Harga Sewa Field
                    TextFormField(
                      controller: _hargaController,
                      keyboardType: TextInputType.number,
                      decoration: const InputDecoration(
                        labelText: 'Harga Sewa per Hari',
                        hintText: '50000',
                        prefixText: 'Rp ',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.all(Radius.circular(8)),
                        ),
                        contentPadding: EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Harga sewa tidak boleh kosong';
                        }
                        final harga = double.tryParse(value.replaceAll(',', ''));
                        if (harga == null || harga <= 0) {
                          return 'Harga sewa harus berupa angka positif';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Status Dropdown
                    DropdownButtonFormField<String>(
                      value: _selectedStatus,
                      decoration: InputDecoration(
                        labelText: 'Status',
                        border: OutlineInputBorder(
                          borderRadius: BorderRadius.circular(8),
                        ),
                        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
                      ),
                      items: _statusList.map((status) {
                        return DropdownMenuItem(
                          value: status,
                          child: Text(status),
                        );
                      }).toList(),
                      onChanged: (value) {
                        setState(() {
                          _selectedStatus = value!;
                        });
                      },
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Status harus dipilih';
                        }
                        return null;
                      },
                    ),
                    const SizedBox(height: 16),

                    // Deskripsi Field
                    CustomTextField(
                      controller: _deskripsiController,
                      labelText: 'Deskripsi (Opsional)',
                      hintText: 'Deskripsikan spesifikasi alat musik...',
                      maxLines: 3,
                    ),
                    const SizedBox(height: 24),

                    // Error Message
                    if (inventoryProvider.error != null)
                      Container(
                        padding: const EdgeInsets.all(12),
                        decoration: BoxDecoration(
                          color: Colors.red[50],
                          borderRadius: BorderRadius.circular(8),
                          border: Border.all(color: Colors.red[200]!),
                        ),
                        child: Text(
                          inventoryProvider.error!,
                          style: const TextStyle(color: Colors.red),
                        ),
                      ),

                    const SizedBox(height: 24),

                    // Submit Button
                    CustomButton(
                      text: 'Tambah Alat Musik',
                      onPressed: _submitForm,
                      isLoading: inventoryProvider.isLoading,
                    ),
                  ],
                ),
              ),
            ),
    );
  }

  Widget _buildImagePreview(InventoryProvider provider) {
    return Column(
      children: [
        const Text(
          'Foto Alat Musik (Opsional)',
          style: TextStyle(fontSize: 16, fontWeight: FontWeight.w500),
        ),
        const SizedBox(height: 12),
        Container(
          width: double.infinity,
          height: 200,
          decoration: BoxDecoration(
            border: Border.all(color: Colors.grey[300]!),
            borderRadius: BorderRadius.circular(12),
            color: Colors.grey[50],
          ),
          child: provider.selectedImage != null
              ? ClipRRect(
                  borderRadius: BorderRadius.circular(12),
                  child: Image.file(
                    provider.selectedImage!,
                    fit: BoxFit.cover,
                  ),
                )
              : Column(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Icon(
                      Icons.image_outlined,
                      size: 48,
                      color: Colors.grey[400],
                    ),
                    const SizedBox(height: 8),
                    Text(
                      'Belum ada gambar dipilih',
                      style: TextStyle(color: Colors.grey[600]),
                    ),
                  ],
                ),
        ),
        const SizedBox(height: 12),
        Row(
          children: [
            Expanded(
              child: OutlinedButton.icon(
                onPressed: () => provider.pickImage(),
                icon: const Icon(Icons.photo_library),
                label: const Text('Pilih dari Galeri'),
                style: OutlinedButton.styleFrom(
                  padding: const EdgeInsets.symmetric(vertical: 12),
                ),
              ),
            ),
            if (provider.selectedImage != null) ...[
              const SizedBox(width: 12),
              IconButton(
                onPressed: () => provider.clearSelectedImage(),
                icon: const Icon(Icons.clear),
                style: IconButton.styleFrom(
                  backgroundColor: Colors.red[50],
                  foregroundColor: Colors.red,
                ),
              ),
            ],
          ],
        ),
      ],
    );
  }

  void _submitForm() async {
    if (!_formKey.currentState!.validate()) return;

    final inventoryProvider = context.read<InventoryProvider>();
    final authProvider = context.read<AuthProvider>();

    if (authProvider.token == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Token tidak ditemukan')),
      );
      return;
    }

    setState(() {
      _isLoading = true;
    });

    try {
      final success = await inventoryProvider.addAlatBand(
        namaAlat: _namaController.text.trim(),
        kategori: _selectedKategori,
        stok: int.parse(_stokController.text),
        hargaSewa: double.parse(_hargaController.text.replaceAll(',', '')),
        deskripsi: _deskripsiController.text.trim().isEmpty
            ? null
            : _deskripsiController.text.trim(),
        status: _selectedStatus,
      );

      if (success && mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Alat musik berhasil ditambahkan')),
        );
        Navigator.pop(context);
      } else if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Error: ${inventoryProvider.error}')),
        );
      }
    } finally {
      if (mounted) {
        setState(() {
          _isLoading = false;
        });
      }
    }
  }
}
