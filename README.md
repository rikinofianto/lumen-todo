# Todos API Task

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Jawaban pertanyaan

#### 1. REST API
Adalah cara komunikasi antar system yang disebut juga service dengan cara yang terstruktur dan menggunakan aturan dan method HTTP yang ada

#### 2. CORS dan cara menangani di backend
CORS adalah keamanan browser yang membatasi akses website ke domain lain, di case saya pada API Lumen ini, saya menggunakan swagger ketika saya deploy ke public domain swager saya tidak muncul karena terkena cors Mix content, jadi saya atur config di .env agar APP_URL mengarah ke domain public saya. begitu juga dengan env SWAGGER_LUME_CONST_HOST, dan saya atur untuk SWAGGER_LUME_FORCE_HTTPS bernilai true agar selalu request https. namun CORS juga bisa dimanfaatkan sebagai security tambahan misal untuk membatasi akses dari domain tertentu dan method tertentu.

#### 3. Perbedaan SQL dan NoSQL
- SQL model data terstruktur dalam table, sedangkan NoSQL lebih variatif ke dokumen file seperti json
- SQL lebih strict karena harus didefinisikan dulu atau gampangnya table harus mendefinisikan kolomnya dulu baru bisa insert data, sedangkan NoSQL tidak perlu seperti itu, cukup define dokumen dan strukturnya dinamis tanpa harus di define terlebih dahulu.
- SQL adalah relational yang mana memudahkan adanya relasi antar table dengan stablil, NoSQL Non-relational tidak mendukung adanya relasi antar dokumen secara default namun masih bisa diatasi dengan ORM jika diperlukan adanya relasi dokumen.

#### 4. Middleware
Middleware ibarat satpam di suatu gedung, dia akan melakukan pengecekan yang diperlukan kepada pengunjung sebelum pengunjung tersebut masuk gedung. atau seperti petugas ticketing disuatu konser yang melakukan validasi atau pengecekan terhadap user yang ingin masuk ke venue untuk menonton konser.

#### 5. CRUD

Untuk dokumentasi CRUD bisa dilihat pada https://todos.api.imonix.my.id/api/documentation
