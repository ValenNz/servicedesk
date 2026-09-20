# Service Desk - Laravel Ticketing System

Sistem manajemen tiket layanan (Service Desk) yang dibangun menggunakan Laravel dengan arsitektur **multi-database** (MySQL + MongoDB).

##  Fitur Utama

### **Multi-Database Architecture**
- **MySQL**: Untuk data terstruktur (Users, Tickets, Categories, Comments)
- **MongoDB**: Untuk data dinamis/log (Activity Logs, Ticket History)

### **Role-Based Access Control**
- **Admin**: Manajemen penuh (users, categories, tickets, assignment)
- **Employee**: Handle tiket yang di-assign, update status
- **User**: Buat tiket, lihat tiket sendiri, komentar

### **Fitur Ticketing**
-  Create, Read, Update, Delete tickets
-  Upload attachment (PDF, JPG, PNG, DOCX - max 5MB)
-  Assign ticket ke employee
-  Status tracking (Open → In Progress → Resolved → Closed)
-  Auto-close ticket (3 hari setelah Resolved)
-  Comment system dengan attachment
-  Activity log & ticket history
-  Search & filter tickets
-  Export activity log (CSV)

---

##  Persyaratan Sistem

- **PHP** >= 8.1
- **Composer**
- **MySQL** >= 5.7
- **MongoDB** >= 4.4
- **Node.js** & **NPM**
- **Git**

---

## 🛠️ Instalasi & Setup

### **1. Clone Repository**

```bash
git clone https://github.com/yourusername/servicedesk-laravel.git
cd servicedesk-laravel
```

### **2. Install Dependencies**

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### **3. Setup Environment**

Salin file environment:
```bash
cp .env.example .env
```

### **4. Konfigurasi Database**

Edit file **`.env`** dan sesuaikan konfigurasi berikut:

```env
# =================================
# MYSQL CONFIGURATION
# =================================
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=service_desk_mysql
DB_USERNAME=root
DB_PASSWORD=

# =================================
# MONGODB CONFIGURATION
# =================================
MONGODB_HOST=127.0.0.1
MONGODB_PORT=27017
MONGODB_DATABASE=service_desk_mongodb
MONGODB_USERNAME=
MONGODB_PASSWORD=
MONGODB_OPTIONS=

# =================================
# APPLICATION CONFIGURATION
# =================================
APP_NAME=ServiceDesk
APP_URL=http://localhost:8000
```

> **Catatan Penting:**
> - Pastikan database MySQL `service_desk_mysql` sudah dibuat
> - Pastikan MongoDB sudah berjalan dan database `service_desk_mongodb` akan dibuat otomatis

### **5. Generate Application Key**

```bash
php artisan key:generate
```

### **6. Jalankan Migration**

```bash
# Buat tabel di MySQL
php artisan migrate

# Buat collections di MongoDB (otomatis)
php artisan migrate --database=mongodb
```

### **7. Seed Database (Data Awal)**

```bash
php artisan db:seed
```

Data yang akan dibuat:
- **Admin**: `admin@servicedesk.com` / `password`
- **Employee**: `employee@servicedesk.com` / `password`
- **User**: `user@servicedesk.com` / `password`
- Categories: Network, Software, Hardware, Access & Account

### **8. Build Asset Frontend**

```bash
npm run build
```

Atau untuk development:
```bash
npm run dev
```

### **9. Jalankan Application**

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```
Aplikasi akan berjalan di: `http://localhost:8000`

**Terminal 2 - MongoDB (jika belum jalan):**
```bash
# Windows (jalankan di folder MongoDB bin)
mongod

# macOS/Linux
sudo systemctl start mongod
# atau
brew services start mongodb-community
```

---

## 🔧 Konfigurasi Tambahan

### **Setup MongoDB Driver untuk PHP**

Jika mendapat error koneksi MongoDB, install driver:

```bash
# Via PECL
pecl install mongodb

# Tambahkan di php.ini
extension=mongodb
```

Restart web server setelah instalasi.

### **Setup Storage Link (Untuk Upload File)**

```bash
php artisan storage:link
```

### **Setup Scheduler (Auto-Close Tickets)**

Agar fitur auto-close berjalan otomatis, tambahkan cron job:

**Di `routes/console.php` (Laravel 11+) atau `app/Console/Kernel.php` (Laravel 10-):**

```php
Schedule::command('tickets:auto-close-resolved')->daily();
```

**Tambahkan di crontab server:**
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

##  Struktur Database

### **MySQL Tables:**
- `users` - User accounts (admin, employee, user)
- `tickets` - Ticket data
- `ticket_comments` - Comments pada ticket
- `categories` - Kategori ticket

### **MongoDB Collections:**
- `activity_logs` - Log aktivitas sistem
- `ticket_histories` - Riwayat perubahan status ticket

---

##  Demo Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@servicedesk.com | password |
| **Employee** | employee@servicedesk.com | password |
| **User** | user@servicedesk.com | password |

---

##  API Endpoints (Optional)

Jika ingin mengembangkan API, endpoint yang tersedia:

- `GET /api/tickets` - List tickets
- `POST /api/tickets` - Create ticket
- `GET /api/tickets/{id}` - Detail ticket
- `PUT /api/tickets/{id}` - Update ticket
- `DELETE /api/tickets/{id}` - Delete ticket

---

## 🐛 Troubleshooting

### **Error: "Column not found: file_name"**
Jalankan migration tambahan:
```bash
php artisan make:migration add_file_columns_to_tickets_table --table=tickets
```

Tambahkan kolom:
```php
$table->string('file_name')->nullable()->after('file_path');
$table->string('file_size')->nullable()->after('file_name');
```

Lalu:
```bash
php artisan migrate
```

### **Error: "Route [activity.export] not defined"**
Pastikan route sudah ditambahkan di `routes/web.php`:
```php
Route::get('/activity/export', [ActivityController::class, 'export'])
    ->name('activity.export');
```

### **MongoDB Connection Refused**
- Pastikan MongoDB service sudah berjalan
- Cek konfigurasi di `.env` (host, port, database name)
- Pastikan PHP MongoDB extension sudah terinstall

---

##  License

Proyek ini dibuat untuk tujuan seleksi CeLoe Staff