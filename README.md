# 🏥 LOGBOOK DINAMIS RUMAH SAKIT (v1.2.0)

System Management Logbook Operasional IT & Layanan Rumah Sakit Terintegrasi berbasis **PHP Native MVC** dengan desain modern, dinamis, dan terenkripsi.

---

## 📌 Review Aplikasi (Application Review)

**Logbook Dinamis Rumah Sakit** dirancang khusus untuk memenuhi kebutuhan operasional rumah sakit modern dalam mencatat, memantau, dan merekapitulasi seluruh insiden layanan IT, gangguan modul SIMRS, pemeliharaan server, insiden jaringan, hingga pencatatan log komite keperawatan (Komkep).

### Mengapa Aplikasi Ini Dibangun?
- **Efisiensi Helpdesk**: Menggantikan pencatatan log manual berbasis kertas/Excel dengan sistem tiket terpusat.
- **Formulir Dinamis (Template Builder)**: Setiap jenis logbook memiliki struktur field unik (contoh: Log Gangguan SIMRS berbeda dengan Log Pemeliharaan Server) yang dapat dikustomisasi tanpa perlu mengubah struktur kode.
- **Target Respon SLA (Service Level Agreement)**: Penanganan insiden terbagi dalam skala prioritas **Tinggi (15 mnt)**, **Sedang (1 jam)**, dan **Rendah (24 jam)**.
- **Keamanan & Transparansi**: Dilengkapi dengan **Role-Based Access Control (RBAC)** 3 tingkat serta **System Audit Log** yang mencatat setiap aksi pengguna secara transparan.

---

## 🚀 Prototipe & Fitur Utama

### 1. 📊 Executive Dashboard & Visual Analytics
- **KPI Real-Time Cards**: Total Logbook, Tiket Open, Dalam Proses, dan Selesai.
- **Interactive Chart.js**: Chart garis tren pelaporan 7 hari terakhir & Chart donat distribusi kategori gangguan.
- **Topbar Header Universal**: Menampilkan Tanggal & Waktu Real-Time, Notification Bell 🔔 dengan status unread, dan Dropdown Profil Pengguna.

### 2. 📋 Manajerial Logbook & Pemfilteran Cerdas
- **Filter Multi-Kriteria**: Filter instant berdasarkan Unit Kerja, Status Tiket, Skala Prioritas, serta pencarian judul/deskripsi.
- **Dynamic Pagination**: Navigasi halaman cepat untuk menangani ribuan data logbook.
- **Export Data**: Dukungan export laporan rekapitulasi data logbook.

### 3. 📐 Dynamic Template Field Builder
- Pengelola (Admin/IT Support) dapat menambah dan menyesuaikan jenis logbook baru lengkap dengan kustomisasi field input (Text, Dropdown, Textarea, Checkbox, File Attachment, dll).

### 4. 🛡️ Role-Based Access Control (RBAC)
- **Super Admin (Role ID 1)**: Akses penuh ke seluruh sistem (Master User, Branding, Settings, Template Builder, Master Data, Audit Log, & Reports).
- **IT Support (Role ID 2)**: Akses penanganan logbook, Master Unit/Kategori/Prioritas, Template Builder, Audit Log, & Reports.
- **Petugas Unit (Role ID 3)**: Pelaporan logbook unit kerja, pemantauan status tiket unit, dan rekapitulasi laporan.

### 5. 🔑 Keamanan Akun & Audit Log System
- Enkripsi Hash Password berbasis **BCRYPT**.
- Form Modal **Ubah Password Akun** terintegrasi.
- **Audit Log System**: Mencatat riwayat *Who, What, When, & Note* pada setiap aktivitas CRUD data.

---

## 🔄 Alur Bisnis Proses Operasional

```
[1. Input Logbook] ➔ [2. Penugasan & SLA] ➔ [3. Tindakan & Solusi] ➔ [4. Audit & Export]
   (Petugas Unit)         (IT Helpdesk)             (Teknisi IT)          (Super Admin)
```

1. **Input Logbook**: Petugas Unit (Rawat Jalan, IGD, Farmasi, Komkep) membuat tiket laporan kendala via Template Logbook Dinamis.
2. **Penugasan IT**: Tiket masuk ke antrean Tim IT Support sesuai kategori & target SLA.
3. **Tindakan Perbaikan**: Teknisi melakukan perbaikan dan memperbarui status tiket (`Open` ➔ `Proses` ➔ `Selesai`).
4. **Audit Log & Export**: Seluruh riwayat terekam di Audit Log System & dapat diexport ke format Excel.

---

## 🛠️ Panduan Instalasi & Penggunaan

### Persyaratan Sistem
- Web Server: XAMPP / Apache / Nginx
- PHP Version: 7.4 / 8.0+
- Database: MySQL / SQLite

### Langkah Instalasi (Localhost)
1. **Clone Repositori**:
   ```bash
   git clone https://github.com/boyank28/logbook-rs.git
   cd logbook-rs
   ```
2. **Jalankan PHP Development Server**:
   ```bash
   php -S 127.0.0.1:8000 -t public
   ```
3. **Akses Aplikasi**:
   Buka browser di `http://127.0.0.1:8000`

### Akun Login Default Demo:
| Peran (Role) | Email | Password Default | Akses Hak |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@rs.id` | `password123` | Akses Penuh Sistem & Pengaturan |
| **IT Support** | `budi@rs.id` | `password123` | Penanganan Tiket & Master Data |
| **Petugas Unit** | `masmul@rs.com` | `password123` | Pelaporan Logbook Unit |

---

## 📁 Struktur Arsitektur Proyek

```
logbook-rs/
├── app/
│   ├── Controllers/     # Logic Controller (Auth, Dashboard, Logbook, Master, Report, Template)
│   ├── Helpers/         # Helper auth, response, upload, & render_topbar
│   └── Models/          # Database Models (User, Unit, Logbook, LogbookTemplate, AuditLog)
├── config/              # App & Database Configuration
├── database/            # Migrations & SQL Seeders
├── public/              # Public Assets (CSS, JS, Uploads, Index Entry)
├── resources/
│   └── views/           # Application Views (Dashboard, Logbook, Master, Reports, Settings)
└── routes/              # Web & API Route Handler (routes/web.php)
```

---

## 📄 Lisensi & Dukungan
Dipublikasikan di bawah lisensi **Open-Source Production**.  
Dukungan pengembang: [Saweria Boyank28](https://saweria.co/boyank28) ☕💛
