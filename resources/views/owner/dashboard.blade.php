@extends('layouts.app')

@section('title', 'Owner Dashboard')

<div>
    <h2 class="text-4xl font-bold">Hello Owner</h2>
    <form action="{{ route('logout') }}" method="POST" class="mt-4">
        @csrf
        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-300">
            Logout
        </button>
    </form>
</div>
