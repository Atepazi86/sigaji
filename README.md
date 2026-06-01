# 💼 SiGaji - Sistem Informasi Penggajian & HR

Aplikasi **Human Resource Information System (HRIS)** dan **Payroll Management** berbasis Laravel 11 dengan arsitektur enterprise-grade.

## 🎯 Fitur Utama

### 1. Dashboard Penggajian (Analytics Center)
- 📊 Ringkasan total karyawan aktif (dengan caching Redis)
- 📈 Grafik pengeluaran gaji bulanan (Chart.js)
- 🔔 Rekap komponen penggajian (Pie/Donut Chart)
- ⚡ Data real-time dengan refresh otomatis

### 2. Manajemen Karyawan (Data Inti)
- 👥 CRUD lengkap untuk data karyawan
- 🏢 Manajemen departemen dan jabatan
- 👨‍👩‍👧‍👦 Status kepegawaian dan keluarga (PTKP)
- 🏦 Data bank dan rekening karyawan
- 🗑️ Soft Delete untuk audit trail

### 3. Komponen Penggajian (Mesin Kalkulasi)
- 💰 Gaji pokok dan tunjangan transportasi
- 🏥 BPJS Ketenagakerjaan & Kesehatan (dinamis)
- 📋 Perhitungan PPh 21 (kompleks dengan PTKP)
- 💳 Pinjaman dan potongan dinamis
- ✅ DECIMAL precision untuk akurasi finansial

### 4. Generate Gaji Massal
- ⚙️ Job Queue untuk proses background
- 📄 Generate Slip Gaji PDF otomatis
- 📧 Email distribusi ke karyawan
- 📊 Export Excel & CSV untuk bank
- 🔄 Batch processing anti-timeout

### 5. Master Data (Referensi Admin)
- 🏷️ Departemen, Jabatan, Bank
- ⚙️ Payroll Settings (BPJS, Tax, dll)
- 🎨 Profil Perusahaan & Logo
- 🔐 Role-Based Access Control (RBAC)

### 6. Landing Page Management
- 📝 Mini-CMS untuk konten publik
- 🎨 Hero section & benefits management
- 💾 Dynamic rendering dari database

## 🛠️ Tech Stack

- **Backend:** Laravel 11 (PHP 8.2+)
- **Database:** MySQL 8.0+
- **Frontend:** Blade Templates + Tailwind CSS
- **Charting:** Chart.js / ApexCharts
- **PDF:** DomPDF
- **Queue:** Redis / Database Jobs
- **Authentication:** Laravel Sanctum / Session
- **Testing:** PHPUnit + Pest

## 📦 Struktur Database

```
Database Tables:
├── users (authentication & user management)
├── employees (data karyawan utama)
├── departments (referensi departemen)
├── positions (referensi jabatan & tier gaji)
├── payroll_components (komponen gaji: gaji pokok, tunjangan, dll)
├── payroll_settings (setting dinamis: % BPJS, tax rate, dll)
├── payroll_transactions (hasil kalkulasi gaji per periode)
├── loans (pinjaman karyawan)
├── deductions (potongan dinamis)
├── bpjs_settings (konfigurasi BPJS)
├── tax_settings (konfigurasi PPh 21 & PTKP)
├── payroll_histories (arsip slip gaji)
└── company_settings (profil perusahaan & logo)
```

## 🚀 Quick Start

```bash
# 1. Clone repository
git clone https://github.com/Atepazi86/sigaji.git
cd sigaji

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Database setup
php artisan migrate --seed

# 5. Build assets
npm install
npm run build

# 6. Start server
php artisan serve
```

## 📋 Roadmap

- [x] Database architecture design
- [x] Authentication & RBAC setup
- [ ] Employee management module
- [ ] Payroll calculation engine
- [ ] Generate gaji batch processing
- [ ] Reporting & export features
- [ ] Landing page CMS
- [ ] Mobile-friendly UI
- [ ] API untuk integrasi

## 👥 User Roles

| Role | Akses |
|------|-------|
| **Admin** | Full akses semua modul |
| **HR Manager** | Employee, Payroll, Reports |
| **Manager** | View laporan team mereka |
| **Karyawan** | Lihat profil & slip gaji sendiri |

## 📞 Support & Dokumentasi

- 📖 [API Documentation](./docs/api.md)
- 🔧 [Development Guide](./docs/development.md)
- 🗄️ [Database Schema](./docs/database.md)

---

**Version:** 1.0.0 Beta  
**License:** MIT  
**Last Updated:** Juni 2026
