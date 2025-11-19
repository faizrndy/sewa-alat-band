@extends('layouts.app')

@section('title', 'Review Pelanggan')
@section('page-title', 'Review Pelanggan')
@section('page-description', 'Kelola screenshot testimoni pelanggan')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Daftar Review Pelanggan</h2>
        <a href="{{ route('review.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            + Tambah Review
        </a>
    </div>

    @if($reviews->isEmpty())
        <p class="text-gray-500 text-center">Belum ada review pelanggan.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($reviews as $review)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset($review->gambar) }}" alt="{{ $review->nama_pelanggan }}" class="w-full h-56 object-cover">
                    <div class="p-4 flex justify-between items-center">
                        <h3 class="font-semibold text-lg text-gray-800">{{ $review->nama_pelanggan }}</h3>
                        <form action="{{ route('review.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Hapus review ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
