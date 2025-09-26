# TugasRequestAPI

## Deskripsi Singkat Program

Aplikasi web sederhana berbasis PHP yang menampilkan data dari berbagai API publik menggunakan teknik HTTP request. Pengguna dapat melihat gambar kucing random, detail negara Asia Tenggara, dan fakta unik yang tidak berguna setiap hari. Semua API dipanggil secara langsung dari server menggunakan PHP.

Proyek ini menampilkan cara melakukan **GET request** ke API publik tanpa framework, memparsing **JSON**, lalu merendernya ke **HTML** yang aman (menggunakan `htmlspecialchars`). Setiap halaman bertanggung jawab untuk:
- Menyusun **stream context** (method, headers, user agent, timeout).
- Melakukan request ke endpoint API.
- **Validasi dan penanganan error** (HTTP gagal/JSON invalid/kosong).
- Menampilkan hasil dalam komponen UI sederhana.

---

## Deskripsi Singkat API yang Digunakan

### 1. The Cat API

-   **Endpoint:** `https://api.thecatapi.com/v1/images/search`
-   **Fungsi:** Mengambil gambar kucing random beserta detail gambar (id, dimensi, breed jika tersedia).
-   **Syarat:** Tidak membutuhkan API key untuk endpoint ini.
-   **Catatan penggunaan di proyek:** Hanya set **User Agent** tanpa header khusus/param tambahan.

### 2. REST Countries API

-   **Endpoint:** `https://restcountries.com/v3.1/name/{country}`
-   **Fungsi:** Mengambil detail negara berdasarkan nama (misal: Indonesia, Malaysia, dll).
-   **Syarat:** Tidak membutuhkan API key.
-   **Catatan penggunaan di proyek:** Negara dibatasi ke **Asia Tenggara** melalui daftar di kode. Response diolah untuk nama, bendera emoji, ibu kota, region/subregion, populasi, area, bahasa, mata uang, domain internet, kode telepon, arah mengemudi, zona waktu, koordinat, hingga tautan peta (jika tersedia dari API).

### 3. Useless Facts API

-   **Endpoint:** `https://uselessfacts.jsph.pl/api/v2/facts/random`
-   **Fungsi:** Mengambil fakta random yang tidak berguna.
-   **Syarat:** Tidak membutuhkan API key.
-   **Catatan penggunaan di proyek:** Mengatur header `Accept: application/json` dan `Content-Type: application/json` pada stream context.

---

## Metode/Teknik API Call Request

-   **Bahasa Pemrograman:** PHP
-   **Teknik Request:** Menggunakan fungsi bawaan PHP `file_get_contents()` dengan konfigurasi stream context untuk menambah header dan user-agent.
-   **Contoh Fungsi:**
    ```php
    $context = stream_context_create([
        'http' => [
            'method' => 'GET',
            'timeout' => 30,
            'user_agent' => 'Mozilla/5.0 ...',
            'header' => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]
    ]);
    $response = @file_get_contents($api_url, false, $context);
    if ($response === false) {
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
    }
    ```

**Keamanan output:** seluruh teks dari API dipasang ke HTML menggunakan `htmlspecialchars($value, ENT_QUOTES, 'UTF-8')` untuk menghindari XSS.

---

## Isi Request dan Response untuk Masing-Masing API Call

### 1. The Cat API

-   **Request:**
    -   Method: GET
    -   URL: `https://api.thecatapi.com/v1/images/search`
    -   Header: User-Agent (optional)
-   **Response:**
    -   Format: JSON Array
    -   Contoh:
        ```json
        [
            {
                "id": "abc123",
                "url": "https://cdn2.thecatapi.com/images/abc123.jpg",
                "width": 1200,
                "height": 800,
                "breeds": []
            }
        ]
        ```
        **Kondisi error yang ditangani:** jaringan/timeout, JSON kosong/tidak sesuai spesifikasi.

### 2. REST Countries API

-   **Request:**
    -   Method: GET
    -   URL: `https://restcountries.com/v3.1/name/indonesia`
    -   Header: Accept: application/json
-   **Response:**
    -   Format: JSON Array
    -   Contoh:
        ```json
        [
            {
                "name": {
                    "common": "Indonesia",
                    "official": "Republic of Indonesia"
                },
                "capital": ["Jakarta"],
                "region": "Asia",
                "subregion": "South-Eastern Asia",
                "population": 273523615,
                "area": 1904569,
                "languages": { "ind": "Indonesian" },
                "currencies": {
                    "IDR": { "name": "Indonesian rupiah", "symbol": "Rp" }
                },
                "flag": "🇮🇩",
                "tld": [".id"],
                "idd": { "root": "+6", "suffixes": ["2"] },
                "car": { "side": "left" },
                "timezones": ["UTC+07:00"],
                "latlng": [-5, 120],
                "maps": { "googleMaps": "https://goo.gl/maps/..." }
            }
        ]
        ```
        **Kondisi error yang ditangani:** negara tidak ditemukan, data parsial, JSON invalid.

### 3. Useless Facts API

-   **Request:**
    -   Method: GET
    -   URL: `https://uselessfacts.jsph.pl/api/v2/facts/random`
    -   Header: Accept: application/json
-   **Response:**
    -   Format: JSON Object
    -   Contoh:
        ```json
        {
            "id": "e1f7b3...",
            "text": "A group of flamingos is called a 'flamboyance'.",
            "source": "uselessfacts.jsph.pl",
            "source_url": "https://uselessfacts.jsph.pl/",
            "language": "en",
            "permalink": "https://uselessfacts.jsph.pl/e1f7b3..."
        }
        ```
        **Kondisi error yang ditangani:** jaringan/timeout, JSON kosong, field tidak tersedia.

---

## Cara Menjalankan Program

1. Pastikan Anda sudah menginstall [Laragon](https://laragon.org/) atau web server lokal lain yang mendukung PHP.
2. Salin seluruh folder `TugasRequestAPI` ke direktori web server Anda (misal: `c:\laragon\www\TugasRequestAPI`).
3. Jalankan web server.
4. Buka browser dan akses: `http://localhost/TugasRequestAPI/index.php`
5. Pilih API yang ingin dicoba dari menu utama.

---

## Contoh Output Program

### 1. Gambar Kucing Random

-   Menampilkan gambar kucing, ID gambar, dimensi, dan breed (jika ada).

![Contoh Output Cat API](https://cdn2.thecatapi.com/images/r_njVlaSz.jpg)

### 2. Detail Negara Asia Tenggara

- Nama & bendera, ibu kota, region/subregion, populasi, luas, bahasa, mata uang (nama & simbol), TLD, kode telepon, arah mengemudi, zona waktu, koordinat, tautan peta.

```
Nama: Indonesia
Ibu Kota: Jakarta
Region: Asia - South-Eastern Asia
Populasi: 273,523,615 jiwa
Luas Area: 1,904,569 km²
Bahasa: Indonesian
Mata Uang: Indonesian rupiah (Rp)
Domain Internet: .id
Kode Telepon: +62
Arah Mengemudi: Left side
Zona Waktu: UTC+07:00
Koordinat: -5°, 120°
Google Maps: [Buka di Google Maps →](https://goo.gl/maps/...)
```

### 3. Fakta Tidak Berguna Hari Ini

- Teks fakta, ID, tautan permalink, sumber & URL sumber, bahasa.

```
"A group of flamingos is called a 'flamboyance'."
Fact ID: e1f7b3...
Permalink: https://uselessfacts.jsph.pl/e1f7b3...
Source: uselessfacts.jsph.pl
Source URL: https://uselessfacts.jsph.pl/
Language: EN
```

---

## Struktur Proyek

```
/index.php                  # Beranda: tautan ke 3 demo
/cat_api.php                # Demo The Cat API
/country_detail_api.php     # Demo REST Countries (ASEAN)
/useless_fact_api.php       # Demo Useless Facts
/readme.md                  # Dokumentasi
```

---

## Penanganan Error & Praktik Baik
- **Timeout & HTTP error**: tampilkan pesan ramah pengguna bila request gagal.
- **Validasi JSON**: cek `json_decode` mengembalikan array yang diharapkan.
- **Escaping output**: gunakan `htmlspecialchars` untuk semua teks dari API.
- **Keterbatasan**: tidak ada retry/backoff; tidak mengatur rate-limit; tidak ada cache.
- **Peluang perbaikan**: ganti `file_get_contents` dengan **cURL** untuk dukungan yang lebih luas, tambahkan **caching** sederhana, serta logging error yang ringkas.

---

> Data diambil langsung dari API publik, setiap refresh akan menampilkan data baru.
