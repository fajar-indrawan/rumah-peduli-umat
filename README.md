# Rumah Peduli Umat - Aplikasi Manajemen Donasi

Aplikasi manajemen donasi berbasis web yang dibuat menggunakan **Nuxt.js** di sisi frontend dan **Laravel (REST API)** di sisi backend.

---

## 🚀 Live Demo (Aplikasi Online)
* **Frontend Web**: https://rumah-peduli-umat.vercel.app
* **Backend API**: https://rumahpedulibackend-y6ip.b4a.run/api

---

## 🛠️ Tech Stack
* **Frontend**: Nuxt.js 3, Tailwind CSS
* **Backend**: Laravel 11 (REST API)
* **Database**: MySQL (Aiven Cloud / Local)
* **Deployment**: Vercel (Frontend) & Back4App (Backend)

---

## ⚙️ Cara Jalankan Project di Lokal

### 1. Prasyarat
* Node.js (v18+)
* PHP (v8.2+)
* Composer
* MySQL Database

---

### 2. Setup Backend (Laravel)
```bash
# Masuk ke folder backend
cd backend

# Install dependencies
composer install

# Salin file .env.example ke .env
cp .env.example .env

# Generate Application Key
php artisan key:generate

# Konfigurasi database di file .env
# Jalankan migrasi dan seeder database
php artisan migrate --seed

# Jalankan server lokal
php artisan serve