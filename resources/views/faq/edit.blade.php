@extends('dashboard')


@section('content')
<div class="container py-6">
    <h2 class="text-2xl font-bold mb-4">Edit FAQ</h2>

    <form action="{{ route('faq.update', $faq->id) }}" method="POST" class="bg-white p-6 rounded shadow">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block font-semibold mb-1">Pertanyaan</label>
            <input type="text" name="question" value="{{ $faq->question }}" class="border rounded w-full p-2" required>
        </div>
        <div class="mb-4">
            <label class="block font-semibold mb-1">Jawaban</label>
            <textarea name="answer" class="border rounded w-full p-2" rows="4" required>{{ $faq->answer }}</textarea>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Perbarui</button>
        <a href="{{ route('faq.index') }}" class="text-slate-600 ml-2">Batal</a>
    </form>
</div>
@endsection
