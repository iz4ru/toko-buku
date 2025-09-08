@extends('layouts.app')

@section('title', 'Admin Dashboard')

<div class="flex-1 flex flex-col">
    <div class="flex-1 flex items-center justify-center transform px-4 py-8">
        <div class="w-full max-w-md text-center">
            <div class="flex flex-col items-center justify-center gap-8">
                <div class="bg-white/30 backdrop-blur-lg rounded-xl shadow-lg p-8 w-full inset-0">
                    <div class="flex flex-col items-center justify-center gap-2 mb-6">
                        <h2 class="text-2xl font-semibold">Welcome to Dashboard, <br> <span
                                class="font-medium text-xl">{{ Auth::user()->name }}</span> </h2>

                        <a href="{{ route('book.index') }}"
                            class="my-1 w-full text-white flex items-center text-center justify-center gap-2 rounded-md px-4 py-2 bg-[#05C1FF] hover:bg-[#0FA3FF] transition ease-in-out duration-300">
                            <i class="fas fa-book"></i>
                            Manage Book</a>

                        {{-- Logout --}}
                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="flex items-center text-center justify-center gap-2 cursor-pointer bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition ease-in-out duration-300">
                                <i class="fas fa-right-from-bracket"></i>
                                Logout
                            </button>
                        </form>

                    </div>
                    <footer>
                        <p class="text-gray-500 text-sm"><span class="font-bold">© {{ date('Y') }} PAPERY
                                &bull;</span> All rights reserved.</p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
