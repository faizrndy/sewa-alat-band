import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../models/transaksi.dart';
import '../providers/auth_provider.dart';
import '../providers/transaksi_provider.dart';

class RiwayatTransaksiScreen extends StatefulWidget {
  const RiwayatTransaksiScreen({Key? key}) : super(key: key);

  @override
  State<RiwayatTransaksiScreen> createState() => _RiwayatTransaksiScreenState();
}

class _RiwayatTransaksiScreenState extends State<RiwayatTransaksiScreen> {
  @override
  void initState() {
    super.initState();
    _loadRiwayatTransaksi();
  }

  Future<void> _loadRiwayatTransaksi() async {
    final authProvider = Provider.of<AuthProvider>(context, listen: false);
    final transaksiProvider = Provider.of<TransaksiProvider>(context, listen: false);

    if (authProvider.token != null && authProvider.user?.nomorTelepon != null) {
      await transaksiProvider.loadRiwayatTransaksi(
        authProvider.token!,
        authProvider.user!.nomorTelepon!,
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Riwayat Transaksi'),
        backgroundColor: Colors.purple.shade700,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadRiwayatTransaksi,
          ),
        ],
      ),
      body: Consumer<TransaksiProvider>(
        builder: (context, transaksiProvider, child) {
          if (transaksiProvider.isLoading) {
            return const Center(child: CircularProgressIndicator());
          }

          if (transaksiProvider.error != null) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  const Icon(Icons.error, size: 64, color: Colors.red),
                  const SizedBox(height: 16),
                  Text(
                    'Error: ${transaksiProvider.error}',
                    textAlign: TextAlign.center,
                    style: const TextStyle(color: Colors.red),
                  ),
                  const SizedBox(height: 16),
                  ElevatedButton(
                    onPressed: _loadRiwayatTransaksi,
                    child: const Text('Retry'),
                  ),
                ],
              ),
            );
          }

          final transaksiList = transaksiProvider.riwayatTransaksi;

          if (transaksiList.isEmpty) {
            return Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Icon(
                    Icons.receipt_long,
                    size: 80,
                    color: Colors.grey.shade400,
                  ),
                  const SizedBox(height: 16),
                  Text(
                    'Belum ada transaksi',
                    style: TextStyle(
                      fontSize: 18,
                      color: Colors.grey.shade600,
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text(
                    'Transaksi sewa alat musik akan muncul di sini',
                    style: TextStyle(
                      color: Colors.grey.shade500,
                    ),
                  ),
                ],
              ),
            );
          }

          return RefreshIndicator(
            onRefresh: _loadRiwayatTransaksi,
            child: ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: transaksiList.length,
              itemBuilder: (context, index) {
                final transaksi = transaksiList[index];
                return _buildTransaksiCard(transaksi);
              },
            ),
          );
        },
      ),
    );
  }

  Widget _buildTransaksiCard(Transaksi transaksi) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 4,
      shape: RoundedRectangleBorder(
        borderRadius: BorderRadius.circular(12),
      ),
      child: InkWell(
        onTap: () => _showTransaksiDetail(transaksi),
        borderRadius: BorderRadius.circular(12),
        child: Padding(
          padding: const EdgeInsets.all(16),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      'TRX-${transaksi.kodeTransaksi}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                      ),
                    ),
                  ),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                    decoration: BoxDecoration(
                      color: transaksi.statusColor.withOpacity(0.1),
                      borderRadius: BorderRadius.circular(12),
                      border: Border.all(color: transaksi.statusColor),
                    ),
                    child: Text(
                      transaksi.statusText,
                      style: TextStyle(
                        color: transaksi.statusColor,
                        fontSize: 12,
                        fontWeight: FontWeight.w500,
                      ),
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 12),

              // Customer Info
              Row(
                children: [
                  const Icon(Icons.person, size: 16, color: Colors.grey),
                  const SizedBox(width: 4),
                  Text(
                    transaksi.nama,
                    style: const TextStyle(fontWeight: FontWeight.w500),
                  ),
                ],
              ),

              const SizedBox(height: 4),

              Row(
                children: [
                  const Icon(Icons.phone, size: 16, color: Colors.grey),
                  const SizedBox(width: 4),
                  Text(
                    transaksi.telepon,
                    style: TextStyle(color: Colors.grey.shade600),
                  ),
                ],
              ),

              const SizedBox(height: 12),

              // Items
              if (transaksi.items != null && transaksi.items!.isNotEmpty)
                ..._buildItemsList(transaksi.items!),

              const Divider(height: 24),

              // Total
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  const Text(
                    'Total Bayar:',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  Text(
                    'Rp ${transaksi.totalBayar.toStringAsFixed(0).replaceAllMapped(
                      RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'),
                      (Match m) => '${m[1]}.'
                    )}',
                    style: TextStyle(
                      fontSize: 16,
                      fontWeight: FontWeight.bold,
                      color: Colors.green.shade600,
                    ),
                  ),
                ],
              ),

              const SizedBox(height: 8),

              // Date
              if (transaksi.createdAt != null)
                Text(
                  'Dibuat: ${_formatDate(transaksi.createdAt!)}',
                  style: TextStyle(
                    fontSize: 12,
                    color: Colors.grey.shade500,
                  ),
                ),
            ],
          ),
        ),
      ),
    );
  }

  List<Widget> _buildItemsList(List<dynamic> items) {
    return [
      const Text(
        'Detail Item:',
        style: TextStyle(
          fontWeight: FontWeight.w500,
          fontSize: 14,
        ),
      ),
      const SizedBox(height: 4),
      ...items.map((item) => Padding(
        padding: const EdgeInsets.only(left: 8, bottom: 4),
        child: Row(
          children: [
            const Icon(Icons.music_note, size: 14, color: Colors.blue),
            const SizedBox(width: 4),
            Expanded(
              child: Text(
                '${item.namaAlat} (${item.jumlah}x) - ${item.lamaHari} hari',
                style: const TextStyle(fontSize: 12),
              ),
            ),
          ],
        ),
      )),
      const SizedBox(height: 8),
    ];
  }

  void _showTransaksiDetail(Transaksi transaksi) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      shape: const RoundedRectangleBorder(
        borderRadius: BorderRadius.vertical(top: Radius.circular(20)),
      ),
      builder: (context) => DraggableScrollableSheet(
        expand: false,
        initialChildSize: 0.7,
        maxChildSize: 0.9,
        builder: (context, scrollController) => SingleChildScrollView(
          controller: scrollController,
          padding: const EdgeInsets.all(24),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              // Header
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(
                    'Detail Transaksi',
                    style: Theme.of(context).textTheme.headlineSmall?.copyWith(
                      fontWeight: FontWeight.bold,
                    ),
                  ),
                  IconButton(
                    onPressed: () => Navigator.pop(context),
                    icon: const Icon(Icons.close),
                  ),
                ],
              ),

              const SizedBox(height: 16),

              // Transaction Code
              Container(
                padding: const EdgeInsets.all(12),
                decoration: BoxDecoration(
                  color: Colors.blue.shade50,
                  borderRadius: BorderRadius.circular(8),
                ),
                child: Row(
                  children: [
                    const Icon(Icons.receipt, color: Colors.blue),
                    const SizedBox(width: 8),
                    Text(
                      'TRX-${transaksi.kodeTransaksi}',
                      style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: Colors.blue,
                      ),
                    ),
                  ],
                ),
              ),

              const SizedBox(height: 24),

              // Status
              _buildDetailRow('Status', transaksi.statusText, transaksi.statusColor),

              // Customer Info
              const SizedBox(height: 16),
              _buildDetailRow('Nama', transaksi.nama),
              _buildDetailRow('Telepon', transaksi.telepon),
              _buildDetailRow('Alamat', transaksi.alamat),

              if (transaksi.deskripsiLokasi != null && transaksi.deskripsiLokasi!.isNotEmpty)
                _buildDetailRow('Deskripsi Lokasi', transaksi.deskripsiLokasi!),

              // Shipping Info
              const SizedBox(height: 16),
              _buildDetailRow('Metode Pengiriman',
                transaksi.metodePengiriman == 'ambil' ? 'Ambil Sendiri' : 'Antar ke Lokasi'
              ),

              if (transaksi.metodePengiriman == 'antar')
                _buildDetailRow('Ongkos Antar', 'Rp ${transaksi.tarifAntar.toStringAsFixed(0)}'),

              // Items Detail
              const SizedBox(height: 24),
              const Text(
                'Detail Item',
                style: TextStyle(
                  fontSize: 18,
                  fontWeight: FontWeight.bold,
                ),
              ),

              if (transaksi.items != null)
                ...transaksi.items!.map((item) => Container(
                  margin: const EdgeInsets.only(top: 12),
                  padding: const EdgeInsets.all(12),
                  decoration: BoxDecoration(
                    color: Colors.grey.shade50,
                    borderRadius: BorderRadius.circular(8),
                  ),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(
                        item.namaAlat,
                        style: const TextStyle(
                          fontWeight: FontWeight.w500,
                          fontSize: 16,
                        ),
                      ),
                      const SizedBox(height: 4),
                      Text(
                        'Jumlah: ${item.jumlah} | Lama: ${item.lamaHari} hari',
                        style: TextStyle(color: Colors.grey.shade600),
                      ),
                      Text(
                        'Periode: ${item.tanggalMulai} - ${item.tanggalSelesai}',
                        style: TextStyle(color: Colors.grey.shade600),
                      ),
                      Text(
                        'Subtotal: Rp ${item.subtotal.toStringAsFixed(0)}',
                        style: const TextStyle(fontWeight: FontWeight.w500),
                      ),
                    ],
                  ),
                )),

              // Cost Summary
              const SizedBox(height: 24),
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.green.shade50,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.green.shade200),
                ),
                child: Column(
                  children: [
                    _buildDetailRow('Total Sewa', 'Rp ${transaksi.totalSewa.toStringAsFixed(0)}'),
                    if (transaksi.metodePengiriman == 'antar')
                      _buildDetailRow('Ongkos Antar', 'Rp ${transaksi.tarifAntar.toStringAsFixed(0)}'),
                    const Divider(),
                    _buildDetailRow('Total Bayar', 'Rp ${transaksi.totalBayar.toStringAsFixed(0)}'),
                  ],
                ),
              ),

              // Date Info
              const SizedBox(height: 16),
              if (transaksi.createdAt != null)
                _buildDetailRow('Tanggal Dibuat', _formatDate(transaksi.createdAt!)),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildDetailRow(String label, String value, [Color? color, bool isTotal = false]) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          SizedBox(
            width: 120,
            child: Text(
              '$label:',
              style: TextStyle(
                fontWeight: isTotal ? FontWeight.bold : FontWeight.w500,
                color: Colors.grey.shade700,
              ),
            ),
          ),
          Expanded(
            child: Text(
              value,
              style: TextStyle(
                fontWeight: isTotal ? FontWeight.bold : FontWeight.w500,
                color: isTotal ? Colors.green.shade700 : (color ?? Colors.black),
                fontSize: isTotal ? 16 : 14,
              ),
            ),
          ),
        ],
      ),
    );
  }

  String _formatDate(DateTime date) {
    return '${date.day}/${date.month}/${date.year} ${date.hour}:${date.minute.toString().padLeft(2, '0')}';
  }
}
