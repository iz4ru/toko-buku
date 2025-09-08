@extends('layouts.app')

@section('title', 'Admin Book Management')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Data Buku</h1>

        <a href="{{ route('admin.dashboard') }}"
            class="inline-block mb-4 px-4 py-2 bg-gray-700 text-white text-sm rounded-lg hover:bg-gray-800 transition">
            Kembali
        </a>

        <div class="overflow-x-auto bg-[#FAFAFA] shadow-md rounded-lg">
            <table class="min-w-full border border-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                    <tr>
                        <th class="px-4 py-2 border">No</th>
                        <th class="px-4 py-2 border">Kode Buku</th>
                        <th class="px-4 py-2 border">Nama Buku</th>
                        <th class="px-4 py-2 border">Penerbit</th>
                        <th class="px-4 py-2 border">Pengarang</th>
                        <th class="px-4 py-2 border">Tahun Terbit</th>
                        <th class="px-4 py-2 border">Kategori</th>
                        <th class="px-4 py-2 border">Stok</th>
                        <th class="px-4 py-2 border">Harga</th>
                        <th class="px-4 py-2 border">Cover Buku</th>
                        <th class="px-4 py-2 border">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        @foreach ($books as $book)
                            <td class="px-4 py-2 border text-center">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 border">{{ $book->book_code }}</td>
                            <td class="px-4 py-2 border">{{ $book->title }}</td>
                            <td class="px-4 py-2 border">{{ $book->publisher }}</td>
                            <td class="px-4 py-2 border">{{ $book->author }}</td>
                            <td class="px-4 py-2 border">{{ $book->publication_year }}</td>
                            <td class="px-4 py-2 border">{{ $book->category->name }}</td>
                            <td class="px-4 py-2 border">{{ $book->bookDetail->stock }}</td>
                            <td class="px-4 py-2 border">{{ $book->bookDetail->price }}</td>
                            <td class="px-4 py-2 border">
                                <img src="{{ Storage::url('/cover') }}" alt="cover" class="h-12 mx-auto rounded">
                            </td>
                            <td class="px-4 py-2 border text-center">
                                <button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Edit</button>
                                <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
                            </td>
                        @endforeach

                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
