@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-center">Daftar Staff</h2>

    <!-- Pesan Sukses/Error -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Tombol Tambah Staff -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('head.create') }}" class="btn btn-sm btn-success">
            <i class="fas fa-user-plus"></i> Tambah Akun Staff
        </a>
    </div>

    <!-- Tabel Data Staff -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($staffs as $staff)
                    <tr>
                        <!-- Email -->
                        <td>{{ $staff->email }}</td>
                        
                        <!-- Aksi -->
                        <td>
                            <div class="btn-group" role="group" aria-label="Aksi Staff">
                                <!-- Reset Password -->
                                <form action="{{ route('head.reset', $staff->id) }}" method="POST" style="margin-right: 5px;">
                                    @csrf
                                    <button type="submit" class="btn btn-warning btn-sm" title="Reset Password" onclick="return confirm('Reset password staff ini?')">
                                        <i class="fas fa-key"></i>
                                    </button>
                                </form>

                                <!-- Hapus Staff -->
                                <form action="{{ route('head.staff.delete', $staff->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus Staff" onclick="return confirm('Hapus staff ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="text-center">Tidak ada STAFF.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
