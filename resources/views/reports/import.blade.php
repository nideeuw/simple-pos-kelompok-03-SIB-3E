@extends('layouts.app')

@section('title', 'Import Produk')

@section('content')
<h1 class="text-lg font-semibold mb-4">Import Produk dari CSV</h1>

@if (session('success'))
<div class="bg-green-50 text-green-700 p-3 rounded-md mb-4">
    {{ session('success') }}
</div>
@endif

<form method="POST" action="{{ route('import.store') }}" enctype="multipart/form-data" class="max-w-md mb-6">
    @csrf
    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV</span>
        <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
        @error('csv')
        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </label>
    <p class="text-xs text-slate-500 mb-3">Column yang dibutuhkan: <code>name</code>, <code>category_id</code>, <code>price</code>.</p>
    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-md text-sm">Import</button>
</form>

<h2 class="text-base font-semibold mt-8 mb-2">Import versi Laravel Excel</h2>
<form method="POST" action="{{ route('import.excel.store') }}" enctype="multipart/form-data" class="max-w-md mb-6">
    @csrf
    <label class="block mb-3">
        <span class="text-sm font-medium">File CSV</span>
        <input type="file" name="csv" accept=".csv" class="mt-1 block w-full text-sm">
    </label>
    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm">Import (Laravel Excel)</button>
</form>

@if(isset($importErrors) && $importErrors->isNotEmpty())
<div class="bg-red-50 border border-red-200 rounded-md p-4 max-w-xl">
    <h2 class="text-sm font-semibold text-red-700 mb-2">Error pada Import Terakhir:</h2>
    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
        @foreach($importErrors as $error)
        <li>Baris {{ $error->row_number }}: {{ $error->error_message }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection
