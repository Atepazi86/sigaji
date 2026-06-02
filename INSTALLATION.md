# 🚀 Setup & Installation Guide

## Prerequisites
- PHP 8.2+
- Composer
- MySQL 8.0+
- Node.js 16+
- npm atau yarn

## Installation Steps

### 1. Clone Repository
```bash
git clone https://github.com/Atepazi86/sigaji.git
cd sigaji
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Database Configuration
Edit `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigaji
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations
```bash
php artisan migrate
php artisan migrate:seed  # Optional: seed dummy data
```

### 6. Build Assets
```bash
npm run build  # Production
# atau
npm run dev    # Development
```

### 7. Generate Storage Link
```bash
php artisan storage:link
```

### 8. Start Development Server
```bash
php artisan serve
# Buka: http://localhost:8000
```

---

## 📋 Project Structure

```
sigaji/
├── app/
│   ├── Models/               # Eloquent Models
│   ├── Http/
│   │   ├── Controllers/      # Business logic
│   │   └── Middleware/       # RBAC middleware
│   └── Services/             # Service classes (PayrollCalculation, dll)
├── database/
│   ├── migrations/           # Database structure
│   └── seeders/              # Dummy data
├── resources/
│   ├── views/                # Blade templates
│   │   ├── layouts/          # Master layouts
│   │   ├── dashboard/        # Dashboard views
│   │   ├── employees/        # Employee management
│   │   ├── attendance/       # Attendance views
│   │   └── salary-templates/ # Template gaji
│   └── css/                  # Tailwind CSS
├── routes/                   # Route definitions
└── tests/                    # Unit & Feature tests
```

---

## 🔐 Default Roles

| Role | Akses |
|------|-------|
| **Admin** | Full akses semua fitur |
| **HR Manager** | Employee, Payroll, Reports |
| **Manager** | View tim mereka saja |
| **Karyawan** | View profil & slip gaji sendiri |

---

## 🎯 Key Features

### ✅ Employee Management
- CRUD karyawan dengan otomatis pembuatan akun
- Data keluarga untuk kalkulasi pajak
- Bank account management

### ✅ Salary Templates
- Template gaji per posisi/role
- Konfigurasi komponen gaji (pokok, tunjangan, dll)
- Aturan potongan abslensi & keterlambatan
- Manajemen cuti tahunan

### ✅ Attendance System
- Check-in/check-out otomatis
- Hitung keterlambatan
- Manajemen status (Hadir, Sakit, Izin, Cuti, Alfa)

### ✅ Automatic Payroll
- Perhitungan gaji otomatis berdasarkan absensi
- PPh 21 dengan PTKP
- BPJS Kesehatan & Ketenagakerjaan
- Manajemen pinjaman & potongan

### ✅ Role-Based Access
- Admin Dashboard dengan analytics
- HR Manager dengan employee management
- Manager dengan team reports
- Karyawan dengan self-service portal

---

## 📧 Email Configuration

Edit `.env`:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@sigaji.local
MAIL_FROM_NAME="SiGaji"
```

---

## 🗄️ Database Schema

### Main Tables:
- `users` - Authentication & user accounts
- `employees` - Employee data
- `departments` - Departemen
- `positions` - Jabatan & tier gaji
- `salary_templates` - Template gaji per posisi
- `attendances` - Daily attendance
- `leave_requests` - Cuti request
- `payroll_transactions` - Hasil kalkulasi gaji
- `payroll_calculation_details` - Detail perhitungan
- `loans` - Pinjaman karyawan

---

## 🔧 Configuration Files

### config/app.php
```php
'timezone' => 'Asia/Jakarta',
'locale' => 'id',
```

### config/database.php
```php
'default' => env('DB_CONNECTION', 'mysql'),
```

---

## 📝 Usage Examples

### Create Employee (dengan auto user creation)
```
POST /employees
- name: "John Doe"
- email: "john@example.com"
- nip: "2024001"
- department_id: 1
- position_id: 2
- role: "karyawan"  // Auto create user account
```

### Employee Check-in
```
POST /attendance/checkin
- Otomatis record waktu & hitung keterlambatan
```

### Calculate Payroll
```
Sistem otomatis menghitung:
- Gaji berdasarkan hari hadir
- Potongan abslensi
- BPJS & PPh 21
- Cicilan pinjaman
```

---

## 🐛 Troubleshooting

### Database migration error
```bash
php artisan migrate:reset
php artisan migrate
```

### Assets not loading
```bash
npm run build
php artisan cache:clear
```

### Email not sending
- Check `.env` MAIL_* configuration
- Use `php artisan tinker` untuk test

---

## 📚 Documentation

- [Database Schema](./docs/database.md)
- [API Documentation](./docs/api.md)
- [Development Guide](./docs/development.md)

---

## 📞 Support

Untuk pertanyaan atau masalah, silakan buat issue di GitHub.

**Version:** 1.0.0 Beta  
**Last Updated:** Juni 2026
