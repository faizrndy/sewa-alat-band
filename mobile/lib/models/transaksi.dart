import 'package:flutter/material.dart';

class Transaksi {
  final int? id;
  final String kodeTransaksi;
  final String nama;
  final String telepon;
  final String alamat;
  final String? deskripsiLokasi;
  final double? lat;
  final double? lon;
  final double? jarakKm;
  final String metodePengiriman;
  final int tarifAntar;
  final int totalSewa;
  final int totalBayar;
  final String? identitas;
  final String? buktiBayar;
  final String status;
  final List<TransaksiItem>? items;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Transaksi({
    this.id,
    required this.kodeTransaksi,
    required this.nama,
    required this.telepon,
    required this.alamat,
    this.deskripsiLokasi,
    this.lat,
    this.lon,
    this.jarakKm,
    required this.metodePengiriman,
    required this.tarifAntar,
    required this.totalSewa,
    required this.totalBayar,
    this.identitas,
    this.buktiBayar,
    required this.status,
    this.items,
    this.createdAt,
    this.updatedAt,
  });

  factory Transaksi.fromJson(Map<String, dynamic> json) {
    return Transaksi(
      id: json['id'],
      kodeTransaksi: json['kode_transaksi'] ?? '',
      nama: json['nama'] ?? '',
      telepon: json['telepon'] ?? '',
      alamat: json['alamat'] ?? '',
      deskripsiLokasi: json['deskripsi_lokasi'],
      lat: json['lat'] != null ? double.parse(json['lat'].toString()) : null,
      lon: json['lon'] != null ? double.parse(json['lon'].toString()) : null,
      jarakKm: json['jarak_km'] != null ? double.parse(json['jarak_km'].toString()) : null,
      metodePengiriman: json['metode_pengiriman'] ?? 'ambil',
      tarifAntar: json['tarif_antar'] ?? 0,
      totalSewa: json['total_sewa'] ?? 0,
      totalBayar: json['total_bayar'] ?? 0,
      identitas: json['identitas'],
      buktiBayar: json['bukti_bayar'],
      status: json['status'] ?? 'pending',
      items: json['items'] != null
          ? List<TransaksiItem>.from(json['items'].map((x) => TransaksiItem.fromJson(x)))
          : null,
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
      'kode_transaksi': kodeTransaksi,
      'nama': nama,
      'telepon': telepon,
      'alamat': alamat,
      'deskripsi_lokasi': deskripsiLokasi,
      'lat': lat,
      'lon': lon,
      'jarak_km': jarakKm,
      'metode_pengiriman': metodePengiriman,
      'tarif_antar': tarifAntar,
      'total_sewa': totalSewa,
      'total_bayar': totalBayar,
      'identitas': identitas,
      'bukti_bayar': buktiBayar,
      'status': status,
      'items': items?.map((item) => item.toJson()).toList(),
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }

  // Helper methods
  String get statusText {
    switch (status) {
      case 'pending':
        return 'Menunggu Pembayaran';
      case 'paid':
        return 'Sudah Dibayar';
      case 'confirmed':
        return 'Dikonfirmasi';
      case 'completed':
        return 'Selesai';
      case 'cancelled':
        return 'Dibatalkan';
      default:
        return 'Unknown';
    }
  }

  Color get statusColor {
    switch (status) {
      case 'pending':
        return Colors.orange;
      case 'paid':
        return Colors.blue;
      case 'confirmed':
        return Colors.green;
      case 'completed':
        return Colors.purple;
      case 'cancelled':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }
}

class TransaksiItem {
  final int? id;
  final int transaksiId;
  final int alatId;
  final String namaAlat;
  final double hargaSewa;
  final int jumlah;
  final String tanggalMulai;
  final String tanggalSelesai;
  final int lamaHari;
  final double subtotal;

  TransaksiItem({
    this.id,
    required this.transaksiId,
    required this.alatId,
    required this.namaAlat,
    required this.hargaSewa,
    required this.jumlah,
    required this.tanggalMulai,
    required this.tanggalSelesai,
    required this.lamaHari,
    required this.subtotal,
  });

  factory TransaksiItem.fromJson(Map<String, dynamic> json) {
    return TransaksiItem(
      id: json['id'],
      transaksiId: json['transaksi_id'] ?? 0,
      alatId: json['alat_id'] ?? 0,
      namaAlat: json['nama_alat'] ?? '',
      hargaSewa: double.parse(json['harga_sewa'].toString()),
      jumlah: json['jumlah'] ?? 0,
      tanggalMulai: json['tanggal_mulai'] ?? '',
      tanggalSelesai: json['tanggal_selesai'] ?? '',
      lamaHari: json['lama_hari'] ?? 0,
      subtotal: double.parse(json['subtotal'].toString()),
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'transaksi_id': transaksiId,
      'alat_id': alatId,
      'nama_alat': namaAlat,
      'harga_sewa': hargaSewa,
      'jumlah': jumlah,
      'tanggal_mulai': tanggalMulai,
      'tanggal_selesai': tanggalSelesai,
      'lama_hari': lamaHari,
      'subtotal': subtotal,
    };
  }
}
