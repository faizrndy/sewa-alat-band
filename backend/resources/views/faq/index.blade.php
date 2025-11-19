@extends('layouts.app')



@section('content')
<div class="container py-6">
    <h2 class="text-2xl font-bold mb-4">Daftar FAQ</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('faq.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ Tambah FAQ</a>

    <div class="mt-6 grid gap-4">
        @foreach($faqs as $faq)
        <div class="bg-white p-4 rounded shadow flex justify-between items-start">
            <div>
                <h3 class="font-semibold text-slate-800">{{ $faq->question }}</h3>
                <p class="text-slate-600 mt-1">{{ $faq->answer }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('faq.edit', $faq->id) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('faq.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-600">Hapus</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
