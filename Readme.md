# Service Desk - Full Stack Web Application

**Penugasan Web Developer**  
**Program Studi Profesi Informatika**

---

##  Informasi Developer

| **Nama** | Nuevalen Refitra Alswando |
|----------|---------------------------|
| **NIM** | 103072430008 |
| **Posisi Dilamar** | Web Developer |
| **Program Studi** | Profesi Informatika |

---

##  Deskripsi Penugasan

### **PENUGASAN 1 – BACKEND & WEB LARAVEL**
Kembangkan prototipe modul sistem tiketing berbasis Laravel (Blade) yang mengimplementasikan arsitektur multi-connection database.

### **PENUGASAN 2 – FRONTEND (Vue.js & Tailwind CSS)**
Kembangkan aplikasi antarmuka web interaktif berbasis Vue.js secara mandiri dengan desain responsif menggunakan Tailwind CSS.

---

##  Ketentuan yang Dipenuhi

### **Database**
 Menggunakan kombinasi 2 database (MySQL dan MongoDB) dalam satu aplikasi  
 Pemisahan peran data:
- **MySQL**: Data terstruktur (Users, Tickets, Categories, Comments)
- **MongoDB**: Data dinamis/log (Activity Logs, Ticket Histories)  
 Total entitas: 4 tabel MySQL + 2 collection MongoDB  
 Skrip seeder untuk data awal

### **Fitur Backend**
 Operasi CRUD pada alur data utama  
 Validasi form menggunakan Form Request Validation  
 Fitur upload file/lampiran dokumen (PDF, JPG, PNG, DOCX - max 5MB)  
 Fitur pencarian (search) dan penyaringan (filter) data  
 Menampilkan riwayat status, log aktivitas, dan thread balasan  
 Auto-close ticket (3 hari setelah status Resolved)  
 Role-based access control (Admin, Employee, User)  
 Assignment ticket ke employee

### **Fitur Frontend**
 Menggunakan Tailwind CSS untuk styling  
 Desain responsif (mobile dan desktop)  
 Menggunakan Vue.js versi terbaru (Composition API)  
 Interaktivitas tinggi dengan Alpine.js  
 Real-time notifications  
 Dynamic filtering dan pagination

---

##  Teknologi yang Digunakan

### **Backend**
- **Laravel 13.x** - PHP Framework
- **MySQL** - Relational Database
- **MongoDB** - NoSQL Database
- **Alpine.js** - Lightweight JavaScript framework

### **Frontend**
- **Vue.js 3** - Progressive JavaScript Framework
- **Tailwind CSS** - Utility-first CSS Framework
- **Pinia** - State Management
- **Vue Router** - Routing
- **Vite** - Build Tool

### **Tools & Libraries**
- Composer (PHP Dependency Manager)
- NPM (Node Package Manager)
- Git (Version Control)

---

##  Struktur Repository

```
servicedesk/
├── servicedesk-laravel/        # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   │   ├── MongoDB/       # MongoDB Models
│   │   │   └── MySQL/         # MySQL Models
│   │   └── Console/Commands/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── resources/
│   │   ├── views/
│   │   └── js/
│   └── routes/
│
└── servicedesk-frontend/       # Frontend Vue.js
    ├── src/
    │   ├── components/
    │   ├── views/
    │   ├── stores/
    │   ├── composables/
    │   └── router/
    └── public/
```

---

## 🛠️ Instalasi & Setup

### **Prerequisites**
- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL >= 5.7
- MongoDB >= 4.4
- Git

---

### **A. Setup Backend (Laravel)**

#### **1. Clone Repository**
```bash
git clone https://github.com/ValenNz/servicedesk.git
cd servicedesk/servicedesk-laravel
```

#### **2. Install Dependencies**
```bash
composer install
npm install
```

#### **3. Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

#### **4. Konfigurasi Database (.env)**

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

> **Penting:** 
> - Buat database MySQL `service_desk_mysql` terlebih dahulu
> - Database MongoDB akan dibuat otomatis

#### **5. Jalankan Migration**
```bash
# MySQL migrations
php artisan migrate

# MongoDB collections (otomatis)
```

#### **6. Seed Database**
```bash
php artisan db:seed
```

**Default Users:**
- **Admin**: `admin@servicedesk.com` / `password`
- **Employee**: `employee@servicedesk.com` / `password`
- **User**: `user@servicedesk.com` / `password`

#### **7. Build Assets**
```bash
npm run build
# atau untuk development
npm run dev
```

#### **8. Jalankan Server**
```bash
php artisan serve
```
Akses: `http://localhost:8000`

---

### **B. Setup Frontend (Vue.js)**

#### **1. Masuk ke Folder Frontend**
```bash
cd servicedesk/servicedesk-frontend
```

#### **2. Install Dependencies**
```bash
npm install
```

#### **3. Jalankan Development Server**
```bash
npm run dev
```

Akses: `http://localhost:5173` (Admin/Employee)  
Akses: `http://localhost:5174` (User)

---

## 📊 Database Schema

### **MySQL Tables**

#### **1. users**
```sql
- id (bigint, primary key)
- name (string)
- email (string, unique)
- password (string)
- role (enum: admin, employee, user)
- status (enum: Active, Inactive)
- phone (string, nullable)
- created_at, updated_at
```

#### **2. tickets**
```sql
- id (bigint, primary key)
- ticket_id (string, unique)
- title (string)
- description (text)
- category_id (foreign key)
- user_id (foreign key - creator)
- assigned_to (foreign key - employee, nullable)
- status (enum: Open, In Progress, Resolved, Closed)
- priority (enum: Low, Medium, High)
- file_path (string, nullable)
- file_name (string, nullable)
- file_size (string, nullable)
- created_at, updated_at
```

#### **3. categories**
```sql
- id (bigint, primary key)
- name (string)
- description (text)
- ticket_count (integer)
- created_at, updated_at
```

#### **4. ticket_comments**
```sql
- id (bigint, primary key)
- ticket_id (foreign key)
- user_id (foreign key)
- comment (text)
- file_path (string, nullable)
- file_name (string, nullable)
- file_size (string, nullable)
- created_at, updated_at
```

### **MongoDB Collections**

#### **1. activity_logs**
```javascript
{
  _id: ObjectId,
  user_id: Number (nullable),
  user_name: String,
  action: String,
  description: String,
  metadata: Object,
  created_at: ISODate
}
```

#### **2. ticket_histories**
```javascript
{
  _id: ObjectId,
  ticket_id: Number,
  action: String,
  old_value: String/Mixed,
  new_value: String/Mixed,
  performed_by: Number,
  performed_at: ISODate
}
```

---

## 🎮 Fitur Utama

### **1. Multi-Role Authentication**
- Login berdasarkan role (Admin, Employee, User)
- Auto-redirect sesuai role setelah login
- Session management dengan Laravel Sanctum

### **2. Ticket Management**
- Create ticket dengan attachment
- Assign ticket ke employee (Admin only)
- Update status ticket
- Auto-close ticket (3 hari setelah Resolved)
- Search & filter tickets
- Real-time ticket tracking

### **3. Comment System**
- Threaded comments pada ticket
- Upload attachment pada comment
- Role-based comment visibility

### **4. Activity Logging**
- MongoDB-based activity logs
- Track semua perubahan ticket
- User activity history
- Export to CSV

### **5. Dashboard**
- Statistik tickets (Total, Open, In Progress, Resolved)
- Recent tickets list
- Visual progress tracker
- Auto-close countdown indicator

---

##  Security Features

- CSRF Protection
- Password Hashing (bcrypt)
- Role-based Middleware
- Form Validation
- File Upload Validation
- SQL Injection Prevention
- XSS Protection

---

##  Responsive Design

- **Mobile First** approach
- Breakpoints: sm (640px), md (768px), lg (1024px), xl (1280px)
- Touch-friendly interface
- Adaptive navigation menu

---

##  Testing

### **Manual Testing**

1. **Login sebagai Admin**
   - Email: `admin@servicedesk.com`
   - Password: `password`
   - Fitur: Manage users, categories, assign tickets

2. **Login sebagai Employee**
   - Email: `employee@servicedesk.com`
   - Password: `password`
   - Fitur: View assigned tickets, update status, add comments

3. **Login sebagai User**
   - Email: `user@servicedesk.com`
   - Password: `password`
   - Fitur: Create tickets, view own tickets, add comments

---

##  API Endpoints (Optional)

Jika dikembangkan menjadi API:

```
GET    /api/tickets           - List all tickets
POST   /api/tickets           - Create new ticket
GET    /api/tickets/{id}      - Get ticket detail
PUT    /api/tickets/{id}      - Update ticket
DELETE /api/tickets/{id}      - Delete ticket
POST   /api/tickets/{id}/comment - Add comment
PUT    /api/tickets/{id}/status  - Update status
POST   /api/tickets/{id}/assign  - Assign to employee
```

---

## 🐛 Troubleshooting

### **Error: "Column not found"**
```bash
php artisan migrate:fresh --seed
```

### **Error: MongoDB Connection Refused**
- Pastikan MongoDB service berjalan
- Cek konfigurasi di `.env`
- Install PHP MongoDB extension: `pecl install mongodb`

### **Error: Route not defined**
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### **Storage Link Issue**
```bash
php artisan storage:link
```

---

##  Auto-Close Scheduler

Untuk mengaktifkan fitur auto-close ticket:

### **Laravel 11+**
Tambahkan di `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('tickets:auto-close-resolved')->daily();
```

### **Laravel 10 ke bawah**
Tambahkan di `app/Console/Kernel.php`:
```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('tickets:auto-close-resolved')->daily();
}
```

### **Setup Cron Job**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

---

##  License

Proyek ini dibuat untuk tujuan seleksi CeLoe Staff

---
