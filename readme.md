# TugasRequestAPI

## Deskripsi Singkat Program

Aplikasi web sederhana berbasis PHP yang menampilkan data dari berbagai API publik menggunakan teknik HTTP request. Pengguna dapat melihat gambar kucing random, detail negara Asia Tenggara, dan fakta unik yang tidak berguna setiap hari. Semua API dipanggil secara langsung dari server menggunakan PHP.

---

## Deskripsi Singkat API yang Digunakan

### 1. The Cat API

-   **Endpoint:** `https://api.thecatapi.com/v1/images/search`
-   **Fungsi:** Mengambil gambar kucing random beserta detail gambar (id, dimensi, breed jika tersedia).
-   **Syarat:** Tidak membutuhkan API key untuk endpoint ini.

### 2. REST Countries API

-   **Endpoint:** `https://restcountries.com/v3.1/name/{country}`
-   **Fungsi:** Mengambil detail negara berdasarkan nama (misal: Indonesia, Malaysia, dll).
-   **Syarat:** Tidak membutuhkan API key.

### 3. Useless Facts API

-   **Endpoint:** `https://uselessfacts.jsph.pl/api/v2/facts/random`
-   **Fungsi:** Mengambil fakta random yang tidak berguna.
-   **Syarat:** Tidak membutuhkan API key.

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
    ```

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

![Contoh Output Cat API](https://cdn2.thecatapi.com/images/r_njVlaSz.jpg)

-   Menampilkan gambar kucing, ID gambar, dimensi, dan breed (jika ada).

### 2. Detail Negara Asia Tenggara

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

```
"A group of flamingos is called a 'flamboyance'."
Fact ID: e1f7b3...
Permalink: https://uselessfacts.jsph.pl/e1f7b3...
Source: uselessfacts.jsph.pl
Source URL: https://uselessfacts.jsph.pl/
Language: EN
```

---

> Data diambil langsung dari API publik, setiap refresh akan menampilkan data baru.
