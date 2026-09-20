#  Service Desk Application

Aplikasi Service Desk (Ticketing System) yang dibangun menggunakan **Vue 3 (Composition API)**, **Vite**, **Tailwind CSS**, dan **Pinia** untuk state management. Aplikasi ini memiliki pemisahan role yang jelas antara **Admin/Employee** dan **Regular User**, dengan backend mock menggunakan **JSON Server**.

##  Tech Stack

- **Frontend Framework**: Vue 3 (Composition API, `<script setup>`)
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **State Management**: Pinia
- **Routing**: Vue Router
- **Icons**: Lucide Vue Next
- **Backend (Mock)**: JSON Server

##  Struktur Proyek

Proyek ini terdiri dari tiga bagian utama:
```text
├── db.json                     # Database mock (JSON Server)
├── servicedesk-admin/          # Portal untuk Admin & Employee (Port 5174)
├── servicedesk-user/           # Portal untuk Regular User (Port 5173)
└── README.md                   # Panduan setup ini
```

## ⚙️ Prasyarat (Prerequisites)

Pastikan Anda telah menginstal:
- [Node.js](https://nodejs.org/) (versi 18.x atau lebih baru)
- [npm](https://www.npmjs.com/) atau [pnpm](https://pnpm.io/) / [yarn](https://yarnpkg.com/)

---

##  Panduan Setup & Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek di lingkungan lokal Anda. **Disarankan untuk membuka 3 terminal terpisah.**

### 1. Clone Repository
```bash
git clone <URL_REPOSITORY_GIT_ANDA>
cd <NAMA_FOLDER_PROYEK>
```

### 2. Instalasi Dependensi Backend (JSON Server)
Pastikan Anda berada di root folder (tempat file `db.json` berada), lalu jalankan:
```bash
npm install -g json-server
# atau jalankan langsung tanpa install global menggunakan npx di langkah berikutnya
```

### 3. Instalasi Dependensi Frontend
Buka terminal baru, lalu instal dependensi untuk kedua aplikasi frontend:

**Untuk Admin Portal:**
```bash
cd servicedesk-admin
npm install
```

**Untuk User Portal:**
```bash
cd servicedesk-user
npm install
```

---

## ▶ Cara Menjalankan Aplikasi

Anda perlu menjalankan **3 proses secara bersamaan** di 3 terminal yang berbeda:

### Terminal 1: Menjalankan Backend (JSON Server)
Di folder root (tempat `db.json` berada):
```bash
npx json-server --watch db.json --port 3000
```
*(Backend akan berjalan di: `http://localhost:3000`)*

### Terminal 2: Menjalankan Admin/Employee Portal
Masuk ke folder admin dan jalankan dev server:
```bash
cd servicedesk-admin
npm run dev
```
*(Aplikasi akan berjalan di: `http://localhost:5174`)*

### Terminal 3: Menjalankan User Portal
Masuk ke folder user dan jalankan dev server:
```bash
cd servicedesk-user
npm run dev
```
*(Aplikasi akan berjalan di: `http://localhost:5173`)*

---

##  Akun Demo (Default Credentials)

Gunakan kredensial berikut untuk menguji fitur berdasarkan role:

| Role | Email | Password | Portal Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@servicedesk.com` | `password` | Port 5174 |
| **Employee** | `employee1@servicedesk.com` | `password` | Port 5174 |
| **User** | `user@google.com` | `password` | Port 5173 |

> **Catatan:** Jika Anda login menggunakan akun Admin/Employee di port 5173 (User Portal), sistem akan otomatis mengalihkan (redirect) Anda ke port 5174, dan sebaliknya.

---

##  Fitur Utama

###  Admin & Employee (Port 5174)
- **Dashboard**: Statistik keseluruhan tiket (Total, Open, In Progress, Resolved).
- **Manajemen Tiket**: Melihat semua tiket, mengubah status, assign ke employee, dan menghapus tiket.
- **Manajemen User**: Menambah, mengedit, dan menghapus akun user/employee.
- **Manajemen Kategori**: Menambah dan mengelola kategori tiket.
- **Activity Log**: Melacak semua aktivitas sistem secara real-time.

###  Regular User (Port 5173)
- **Dashboard Personal**: Statistik tiket yang dibuat atau di-assign ke user tersebut.
- **Buat Tiket**: Form pembuatan tiket baru dengan pilihan kategori, prioritas, dan lampiran file.
- **Daftar & Detail Tiket**: Melihat progres tiket dan memberikan balasan/komentar.
- **Profil**: Mengelola informasi pribadi dan mengubah password.

---

##  Troubleshooting

1. **Port sudah digunakan (EADDRINUSE)**:
   Jika port 3000, 5173, atau 5174 sudah digunakan, hentikan proses yang berjalan di port tersebut atau ubah port di file konfigurasi (`vite.config.js` atau perintah `json-server`).
2. **Data tidak muncul / Error 404**:
   Pastikan Terminal 1 (JSON Server) sedang berjalan dan file `db.json` berada di direktori yang benar.
3. **Activity Log kosong**:
   Pastikan Anda telah melakukan aksi (login, buat tiket, ubah status) karena data activity dihasilkan secara dinamis saat ada interaksi.

---

##  Lisensi
Proyek ini dibuat untuk tujuan seleksi CeLoe Staff 