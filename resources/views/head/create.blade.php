@extends('layouts.app')

@section('content')
    <h2>Tambah Staff</h2>

    <form action="{{ route('head.staff.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" name="email" class="form-control" required>
            @error('email')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password:</label>
            <input type="password" name="password" class="form-control" required>
            @error('password')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <!-- Provinsi dihapus karena sudah otomatis berdasarkan HEAD_STAFF yang sedang login -->

        <button type="submit" class="btn btn-primary">Tambah Staff</button>
    </form>
@endsection
