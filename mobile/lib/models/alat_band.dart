import 'package:flutter/material.dart';

class AlatBand {
  final int? id;
  final String namaAlat;
  final String kategori;
  final int stok;
  final double hargaSewa;
  final String? deskripsi;
  final String status;
  final DateTime? createdAt;
  final DateTime? updatedAt;

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

  factory AlatBand.fromJson(Map<String, dynamic> json) {
    return AlatBand(
      id: json['id'],
      namaAlat: json['nama_alat'] ?? '',
      kategori: json['kategori'] ?? '',
      stok: json['stok'] ?? 0,
      hargaSewa: double.parse(json['harga_sewa'].toString()),
      deskripsi: json['deskripsi'],
      status: json['status'] ?? 'Tersedia',
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'nama_alat': namaAlat,
      'kategori': kategori,
      'stok': stok,
      'harga_sewa': hargaSewa,
      'deskripsi': deskripsi,
      'status': status,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  // Helper methods
  bool get isAvailable => status == 'Tersedia' && stok > 0;

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

  @override
  String toString() {
    return 'AlatBand(id: $id, nama: $namaAlat, kategori: $kategori, status: $status)';
  }
}
