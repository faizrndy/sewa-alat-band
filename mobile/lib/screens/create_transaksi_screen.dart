import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:image_picker/image_picker.dart';
import '../models/alat_band.dart';
import '../providers/auth_provider.dart';
import '../providers/transaksi_provider.dart';
import '../widgets/custom_button.dart';
import '../widgets/custom_text_field.dart';

class CreateTransaksiScreen extends StatefulWidget {
  final AlatBand alat;

  const CreateTransaksiScreen({Key? key, required this.alat}) : super(key: key);

  @override
  State<CreateTransaksiScreen> createState() => _CreateTransaksiScreenState();
}

class _CreateTransaksiScreenState extends State<CreateTransaksiScreen> {
  final _formKey = GlobalKey<FormState>();

  // Form controllers
  final _namaController = TextEditingController();
  final _teleponController = TextEditingController();
  final _alamatController = TextEditingController();
  final _deskripsiLokasiController = TextEditingController();

  // Transaction data
  DateTime? _tanggalMulai;
  DateTime? _tanggalSelesai;
  int _jumlah = 1;
  String _metodePengiriman = 'ambil';
  File? _identitasFile;
  String? _identitasBase64;

  // Calculated values
  int _lamaHari = 0;
  double _totalSewa = 0;
  int _tarifAntar = 0;
  double _totalBayar = 0;

  @override
  void initState() {
    super.initState();
    // Pre-fill with user data if available
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    if (authProvider.user != null) {
      _namaController.text = authProvider.user!.namaLengkap ?? authProvider.user!.name;
      _teleponController.text = authProvider.user!.nomorTelepon ?? '';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Sewa Alat Musik'),
        backgroundColor: Colors.purple.shade700,
        foregroundColor: Colors.white,
      ),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16.0),
        child: Form(
          key: _formKey,
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Alat yang akan disewa
              _buildAlatInfo(),

              const SizedBox(height: 24),

              // Form Data Diri
              _buildPersonalInfo(),

              const SizedBox(height: 24),

              // Form Sewa
              _buildRentalInfo(),

              const SizedBox(height: 24),

              // Upload Identitas
              _buildIdentitasUpload(),

              const SizedBox(height: 24),

              // Ringkasan Biaya
              _buildCostSummary(),

              const SizedBox(height: 32),

              // Submit Button
              Consumer<TransaksiProvider>(
                builder: (context, transaksiProvider, child) {
                  return CustomButton(
                    text: 'Ajukan Sewa',
                    isLoading: transaksiProvider.isLoading,
                    onPressed: transaksiProvider.isLoading ? null : _submitTransaksi,
                  );
                },
              ),

              // Error message
              Consumer<TransaksiProvider>(
                builder: (context, transaksiProvider, child) {
                  if (transaksiProvider.error != null) {
                    return Container(
                      margin: const EdgeInsets.only(top: 16),
                      padding: const EdgeInsets.all(12),
                      decoration: BoxDecoration(
                        color: Colors.red.shade50,
                        borderRadius: BorderRadius.circular(8),
                        border: Border.all(color: Colors.red.shade200),
                      ),
                      child: Row(
                        children: [
                          Icon(Icons.error, color: Colors.red.shade600),
                          const SizedBox(width: 8),
                          Expanded(
                            child: Text(
                              transaksiProvider.error!,
                              style: TextStyle(color: Colors.red.shade600),
                            ),
                          ),
                        ],
                      ),
                    );
                  }
                  return const SizedBox.shrink();
                },
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildAlatInfo() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.blue.shade50,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.blue.shade200),
      ),
      child: Row(
        children: [
          Container(
            width: 60,
            height: 60,
            decoration: BoxDecoration(
              color: Colors.blue.shade100,
              borderRadius: BorderRadius.circular(8),
            ),
            child: const Icon(
              Icons.music_note,
              color: Colors.blue,
              size: 30,
            ),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  widget.alat.namaAlat,
                  style: const TextStyle(
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                Text(
                  widget.alat.kategori,
                  style: TextStyle(color: Colors.grey.shade600),
                ),
                Text(
                  'Rp ${widget.alat.hargaSewa.toStringAsFixed(0)}/hari',
                  style: TextStyle(
                    color: Colors.green.shade600,
                    fontWeight: FontWeight.w500,
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildPersonalInfo() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Data Diri',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 16),

        CustomTextField(
          controller: _namaController,
          labelText: 'Nama Lengkap',
          hintText: 'Masukkan nama lengkap',
          prefixIcon: Icons.person,
          validator: (value) {
            if (value == null || value.isEmpty) {
              return 'Nama tidak boleh kosong';
            }
            return null;
          },
        ),

        const SizedBox(height: 16),

        CustomTextField(
          controller: _teleponController,
          labelText: 'Nomor Telepon',
          hintText: 'Masukkan nomor telepon',
          keyboardType: TextInputType.phone,
          prefixIcon: Icons.phone,
          validator: (value) {
            if (value == null || value.isEmpty) {
              return 'Nomor telepon tidak boleh kosong';
            }
            if (!RegExp(r'^[0-9]{10,13}$').hasMatch(value)) {
              return 'Format nomor telepon tidak valid';
            }
            return null;
          },
        ),

        const SizedBox(height: 16),

        CustomTextField(
          controller: _alamatController,
          labelText: 'Alamat Lengkap',
          hintText: 'Masukkan alamat lengkap',
          maxLines: 3,
          prefixIcon: Icons.location_on,
          validator: (value) {
            if (value == null || value.isEmpty) {
              return 'Alamat tidak boleh kosong';
            }
            return null;
          },
        ),

        const SizedBox(height: 16),

        CustomTextField(
          controller: _deskripsiLokasiController,
          labelText: 'Deskripsi Lokasi (Opsional)',
          hintText: 'Patokan atau deskripsi tambahan',
          maxLines: 2,
          prefixIcon: Icons.description,
        ),
      ],
    );
  }

  Widget _buildRentalInfo() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Informasi Sewa',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 16),

        // Jumlah
        Row(
          children: [
            const Text('Jumlah: ', style: TextStyle(fontWeight: FontWeight.w500)),
            IconButton(
              onPressed: () {
                if (_jumlah > 1) {
                  setState(() => _jumlah--);
                  _calculateTotal();
                }
              },
              icon: const Icon(Icons.remove_circle),
              color: Colors.red,
            ),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey),
                borderRadius: BorderRadius.circular(8),
              ),
              child: Text(
                _jumlah.toString(),
                style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
              ),
            ),
            IconButton(
              onPressed: () {
                if (_jumlah < widget.alat.stok) {
                  setState(() => _jumlah++);
                  _calculateTotal();
                }
              },
              icon: const Icon(Icons.add_circle),
              color: Colors.green,
            ),
          ],
        ),

        const SizedBox(height: 16),

        // Tanggal Mulai
        ListTile(
          title: const Text('Tanggal Mulai'),
          subtitle: Text(_tanggalMulai != null
              ? '${_tanggalMulai!.day}/${_tanggalMulai!.month}/${_tanggalMulai!.year}'
              : 'Pilih tanggal mulai'),
          trailing: const Icon(Icons.calendar_today),
          onTap: () => _selectDate(true),
        ),

        // Tanggal Selesai
        ListTile(
          title: const Text('Tanggal Selesai'),
          subtitle: Text(_tanggalSelesai != null
              ? '${_tanggalSelesai!.day}/${_tanggalSelesai!.month}/${_tanggalSelesai!.year}'
              : 'Pilih tanggal selesai'),
          trailing: const Icon(Icons.calendar_today),
          onTap: () => _selectDate(false),
        ),

        const SizedBox(height: 16),

        // Metode Pengiriman
        DropdownButtonFormField<String>(
          value: _metodePengiriman,
          decoration: const InputDecoration(
            labelText: 'Metode Pengiriman',
            border: OutlineInputBorder(),
          ),
          items: const [
            DropdownMenuItem(value: 'ambil', child: Text('Ambil Sendiri')),
            DropdownMenuItem(value: 'antar', child: Text('Antar ke Lokasi')),
          ],
          onChanged: (value) {
            setState(() => _metodePengiriman = value!);
            _calculateTotal();
          },
        ),
      ],
    );
  }

  Widget _buildIdentitasUpload() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text(
          'Upload Identitas',
          style: TextStyle(
            fontSize: 18,
            fontWeight: FontWeight.bold,
          ),
        ),
        const SizedBox(height: 8),
        const Text(
          'Upload KTP atau SIM untuk verifikasi',
          style: TextStyle(color: Colors.grey),
        ),
        const SizedBox(height: 16),

        GestureDetector(
          onTap: _pickIdentitasImage,
          child: Container(
            height: 150,
            width: double.infinity,
            decoration: BoxDecoration(
              border: Border.all(color: Colors.grey),
              borderRadius: BorderRadius.circular(12),
              color: Colors.grey.shade50,
            ),
            child: _identitasFile != null
                ? ClipRRect(
                    borderRadius: BorderRadius.circular(12),
                    child: Image.file(
                      _identitasFile!,
                      fit: BoxFit.cover,
                    ),
                  )
                : const Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Icon(
                        Icons.camera_alt,
                        size: 48,
                        color: Colors.grey,
                      ),
                      SizedBox(height: 8),
                      Text(
                        'Tap untuk upload gambar',
                        style: TextStyle(color: Colors.grey),
                      ),
                    ],
                  ),
          ),
        ),
      ],
    );
  }

  Widget _buildCostSummary() {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.green.shade50,
        borderRadius: BorderRadius.circular(12),
        border: Border.all(color: Colors.green.shade200),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Text(
            'Ringkasan Biaya',
            style: TextStyle(
              fontSize: 18,
              fontWeight: FontWeight.bold,
              color: Colors.green,
            ),
          ),
          const SizedBox(height: 16),

          _buildCostRow('Harga Sewa (${_lamaHari} hari)', _totalSewa),
          if (_metodePengiriman == 'antar')
            _buildCostRow('Ongkos Antar', _tarifAntar),

          const Divider(),

          _buildCostRow('Total Bayar', _totalBayar, isTotal: true),
        ],
      ),
    );
  }

  Widget _buildCostRow(String label, dynamic amount, {bool isTotal = false}) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        mainAxisAlignment: MainAxisAlignment.spaceBetween,
        children: [
          Text(
            label,
            style: TextStyle(
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
              fontSize: isTotal ? 16 : 14,
            ),
          ),
          Text(
            'Rp ${amount.toStringAsFixed(0).replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}',
            style: TextStyle(
              fontWeight: isTotal ? FontWeight.bold : FontWeight.normal,
              fontSize: isTotal ? 16 : 14,
              color: isTotal ? Colors.green.shade700 : Colors.black,
            ),
          ),
        ],
      ),
    );
  }

  Future<void> _selectDate(bool isStartDate) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: DateTime.now(),
      firstDate: DateTime.now(),
      lastDate: DateTime.now().add(const Duration(days: 365)),
    );

    if (picked != null) {
      setState(() {
        if (isStartDate) {
          _tanggalMulai = picked;
        } else {
          _tanggalSelesai = picked;
        }
        _calculateTotal();
      });
    }
  }

  Future<void> _pickIdentitasImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);

    if (pickedFile != null) {
      setState(() {
        _identitasFile = File(pickedFile.path);
      });

      // Convert to base64
      final bytes = await _identitasFile!.readAsBytes();
      _identitasBase64 = base64Encode(bytes);
    }
  }

  void _calculateTotal() {
    if (_tanggalMulai != null && _tanggalSelesai != null) {
      _lamaHari = _tanggalSelesai!.difference(_tanggalMulai!).inDays;
      if (_lamaHari < 1) _lamaHari = 1; // Minimum 1 hari

      _totalSewa = _lamaHari * widget.alat.hargaSewa * _jumlah;

      // Hitung tarif antar (misal Rp 25.000 per 5km)
      _tarifAntar = _metodePengiriman == 'antar' ? 25000 : 0;

      _totalBayar = _totalSewa + _tarifAntar;
    }
  }

  Future<void> _submitTransaksi() async {
    if (!_formKey.currentState!.validate()) return;

    if (_tanggalMulai == null || _tanggalSelesai == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Pilih tanggal sewa terlebih dahulu')),
      );
      return;
    }

    if (_identitasBase64 == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Upload identitas terlebih dahulu')),
      );
      return;
    }

    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final transaksiProvider = Provider.of<TransaksiProvider>(context, listen: false);

    if (authProvider.token == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Silakan login terlebih dahulu')),
      );
      return;
    }

    // Create transaction items
    final items = [
      {
        'id': widget.alat.id,
        'nama_alat': widget.alat.namaAlat,
        'harga_sewa': widget.alat.hargaSewa,
        'jumlah': _jumlah,
        'tanggalMulai': _tanggalMulai!.toIso8601String().split('T')[0],
        'tanggalSelesai': _tanggalSelesai!.toIso8601String().split('T')[0],
      }
    ];

    final result = await transaksiProvider.createTransaksi(
      authProvider.token!,
      nama: _namaController.text,
      telepon: _teleponController.text,
      alamat: _alamatController.text,
      deskripsiLokasi: _deskripsiLokasiController.text.isNotEmpty
          ? _deskripsiLokasiController.text
          : null,
      lat: -6.2088, // Default Jakarta coordinates
      lon: 106.8456,
      jarakKm: 5, // Default distance
      metodePengiriman: _metodePengiriman,
      tarifAntar: _tarifAntar,
      totalSewa: _totalSewa.toInt(),
      totalBayar: _totalBayar.toInt(),
      identitas: _identitasBase64!,
      items: items,
    );

    if (result != null && mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
          content: Text('Transaksi berhasil dibuat! Kode: ${result['kode_transaksi']}'),
          backgroundColor: Colors.green,
        ),
      );

      // Navigate back to profile or home
      Navigator.pop(context);
    }
  }

  @override
  void dispose() {
    _namaController.dispose();
    _teleponController.dispose();
    _alamatController.dispose();
    _deskripsiLokasiController.dispose();
    super.dispose();
  }
}
