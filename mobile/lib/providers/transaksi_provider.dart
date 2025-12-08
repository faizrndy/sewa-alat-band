import 'package:flutter/material.dart';
import '../models/transaksi.dart';
import '../services/api_service.dart';

class TransaksiProvider with ChangeNotifier {
  List<Transaksi> _riwayatTransaksi = [];
  bool _isLoading = false;
  String? _error;

  List<Transaksi> get riwayatTransaksi => _riwayatTransaksi;
  bool get isLoading => _isLoading;
  String? get error => _error;

  // Get transaction history
  Future<bool> loadRiwayatTransaksi(String token, String telepon) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await TransaksiApi.getRiwayat(token, telepon);

      if (response['success'] == true && response['data'] != null) {
        _riwayatTransaksi = List<Transaksi>.from(
          response['data'].map((json) => Transaksi.fromJson(json))
        );
        _isLoading = false;
        notifyListeners();
        return true;
      } else {
        throw Exception('Invalid response format');
      }
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return false;
    }
  }

  // Create new transaction
  Future<Map<String, dynamic>?> createTransaksi(
    String token, {
    required String nama,
    required String telepon,
    required String alamat,
    String? deskripsiLokasi,
    required double lat,
    required double lon,
    required double jarakKm,
    required String metodePengiriman,
    required int tarifAntar,
    required int totalSewa,
    required int totalBayar,
    required String identitas,
    required List<Map<String, dynamic>> items,
  }) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    try {
      final response = await TransaksiApi.createTransaksi(
        token,
        nama: nama,
        telepon: telepon,
        alamat: alamat,
        deskripsiLokasi: deskripsiLokasi,
        lat: lat,
        lon: lon,
        jarakKm: jarakKm,
        metodePengiriman: metodePengiriman,
        tarifAntar: tarifAntar,
        totalSewa: totalSewa,
        totalBayar: totalBayar,
        identitas: identitas,
        items: items,
      );

      if (response['success'] == true) {
        _isLoading = false;
        notifyListeners();
        return response;
      } else {
        throw Exception(response['message'] ?? 'Failed to create transaction');
      }
    } catch (e) {
      _error = e.toString();
      _isLoading = false;
      notifyListeners();
      return null;
    }
  }

  // Check availability
  Future<Map<String, dynamic>?> checkAvailability(
    int alatId,
    String tanggalMulai,
    String tanggalSelesai,
    int jumlahDiminta,
  ) async {
    try {
      final response = await TransaksiApi.checkAvailability(
        alatId,
        tanggalMulai,
        tanggalSelesai,
        jumlahDiminta,
      );

      return response;
    } catch (e) {
      _error = e.toString();
      notifyListeners();
      return null;
    }
  }

  // Clear error
  void clearError() {
    _error = null;
    notifyListeners();
  }

  // Refresh data
  Future<void> refreshRiwayat(String token, String telepon) async {
    await loadRiwayatTransaksi(token, telepon);
  }
}
