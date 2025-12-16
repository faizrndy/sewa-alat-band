import 'package:flutter/material.dart';

// Model data untuk alat musik
// Representasi data dari database yang dikirim API
class AlatBand {
  final int? id;           // Primary key dari database
  final String namaAlat;   // Nama alat musik
  final String kategori;   // Kategori (Gitar, Bass, dll)
  final int stok;          // Jumlah stok tersedia
  final double hargaSewa;  // Harga sewa per hari
  final String? deskripsi; // Deskripsi opsional
  final String status;     // Status (Tersedia, Disewa, Dalam Perbaikan)
  final DateTime? createdAt; // Waktu dibuat
  final DateTime? updatedAt; // Waktu diupdate

  // Constructor - semua field kecuali deskripsi wajib diisi
  AlatBand({
    this.id,
    required this.namaAlat,
    required this.kategori,
    required this.stok,
    required this.hargaSewa,
    this.deskripsi,
    required this.status,
    this.createdAt,
    this.updatedAt,
  });

  // Factory constructor buat parse dari JSON API response
  factory AlatBand.fromJson(Map<String, dynamic> json) {
    return AlatBand(
      id: json['id'],
      namaAlat: json['nama_alat'] ?? '',
      kategori: json['kategori'] ?? '',
      stok: json['stok'] ?? 0,
      // Harga bisa dikirim sebagai string atau number, jadi parse dulu
      hargaSewa: double.parse(json['harga_sewa'].toString()),
      deskripsi: json['deskripsi'],
      status: json['status'] ?? 'Tersedia',
      // Parse tanggal kalau ada
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  // Convert object jadi Map untuk dikirim ke API
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama_alat': namaAlat,
      'kategori': kategori,
      'stok': stok,
      'harga_sewa': hargaSewa,
      'deskripsi': deskripsi,
      'status': status,
      // Convert DateTime jadi string ISO format
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  // ==================== HELPER METHODS ====================

  // Cek apakah alat bisa disewa (ada stok dan status tersedia)
  bool get isAvailable => status == 'Tersedia' && stok > 0;

  // Text yang lebih readable untuk status
  String get statusText {
    switch (status) {
      case 'Tersedia':
        return 'Tersedia';
      case 'Disewa':
        return 'Sedang Disewa';
      case 'Dalam Perbaikan':
        return 'Dalam Perbaikan';
      default:
        return 'Unknown';
    }
  }

  // Color sesuai status buat UI
  Color get statusColor {
    switch (status) {
      case 'Tersedia':
        return Colors.green;
      case 'Disewa':
        return Colors.orange;
      case 'Dalam Perbaikan':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  // Debug string buat logging
  @override
  String toString() {
    return 'AlatBand(id: $id, nama: $namaAlat, kategori: $kategori, status: $status)';
  }
}
