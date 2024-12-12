@extends('layouts.app')
@section('title', 'Article Report')

@section('page-title', 'Membuat Pengaduan') 
@section('breadcrumb', 'Membuat Pengaduan')

@section('content')
    <div class="container mt-2">
        <div class="card shadow-sm" style="min-height: 80vh; padding: 20px;">
            <div class="card-body">
                <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="province" class="form-label fw-bold">Provinsi</label>
                        <select id="province" name="province" class="form-select" required>
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="regency" class="form-label fw-bold">Kabupaten/Kota</label>
                        <select id="regency" name="regency" class="form-select" required>
                            <option value="">Pilih Kabupaten/Kota</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="subdistrict" class="form-label fw-bold">Kecamatan</label>
                        <select id="subdistrict" name="subdistrict" class="form-select" required>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="village" class="form-label fw-bold">Desa/Kelurahan</label>
                        <select id="village" name="village" class="form-select" required>
                            <option value="">Pilih Desa/Kelurahan</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="type" class="form-label fw-bold">Tipe Laporan</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="">Pilih Tipe</option>
                            <option value="KEJAHATAN">Kejahatan</option>
                            <option value="PEMBANGUNAN">Pembangunan</option>
                            <option value="SOSIAL">Sosial</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="image" class="form-label fw-bold">Unggah Gambar</label>
                        <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Deskripsi</label>
                        <textarea name="description" id="description" rows="3" class="form-control"
                            placeholder="Masukkan deskripsi laporan" required></textarea>
                    </div>

                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="statement" name="statement" value="1"
                            required>
                        <label class="form-check-label fw-bold" for="statement">
                            Saya menyatakan bahwa data yang saya masukkan adalah benar
                        </label>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const provinceSelect = document.getElementById("province");
            const regencySelect = document.getElementById("regency");
            const subdistrictSelect = document.getElementById("subdistrict");
            const villageSelect = document.getElementById("village");

            fetch("https://www.emsifa.com/api-wilayah-indonesia/api/provinces.json")
                .then(response => response.json())
                .then(data => {
                    data.forEach(province => {
                        const option = document.createElement("option");
                        option.value = province.id;
                        option.textContent = province.name;
                        provinceSelect.appendChild(option);
                    });
                });

            provinceSelect.addEventListener("change", function() {
                const provinceId = this.value;
                regencySelect.innerHTML = "<option value=''>Pilih Kabupaten/Kota</option>";
                subdistrictSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";
                villageSelect.innerHTML = "<option value=''>Pilih Desa/Kelurahan</option>";

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/regencies/${provinceId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(regency => {
                            const option = document.createElement("option");
                            option.value = regency.id;
                            option.textContent = regency.name;
                            regencySelect.appendChild(option);
                        });
                    });
            });

            regencySelect.addEventListener("change", function() {
                const regencyId = this.value;
                subdistrictSelect.innerHTML = "<option value=''>Pilih Kecamatan</option>";
                villageSelect.innerHTML = "<option value=''>Pilih Desa/Kelurahan</option>";

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/districts/${regencyId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(subdistrict => {
                            const option = document.createElement("option");
                            option.value = subdistrict.id;
                            option.textContent = subdistrict.name;
                            subdistrictSelect.appendChild(option);
                        });
                    });
            });

            subdistrictSelect.addEventListener("change", function() {
                const subdistrictId = this.value;
                villageSelect.innerHTML = "<option value=''>Pilih Desa/Kelurahan</option>";

                fetch(`https://www.emsifa.com/api-wilayah-indonesia/api/villages/${subdistrictId}.json`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(village => {
                            const option = document.createElement("option");
                            option.value = village.id;
                            option.textContent = village.name;
                            villageSelect.appendChild(option);
                        });
                    });
            });
        });
    </script>
@endsection
