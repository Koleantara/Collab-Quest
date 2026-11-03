# Kontrak API To-Do List

## 1. Authentication & Profile Endpoints
- **POST** `/api/register`
  - Input JSON: `name`, `email`, `password`, `password_confirmation`
  - Response: `201 Created`
- **POST** `/api/login`
  - Input JSON: `email`, `password`
  - Response: `200 OK` + `access_token`
- **GET** `/api/profile` (Wajib membawa Bearer Token)
  - Response: `200 OK` + Data User Login (`id`, `name`, `email`)
- **POST** `/api/logout` (Wajib membawa Bearer Token)
  - Response: `200 OK`

## 2. To-Do List CRUD Endpoints (Semua wajib membawa Bearer Token)
- **GET** `/api/todos` (Mengambil semua tugas milik user yang sedang login)
  - Response: `200 OK` + Array Data (`id`, `title`, `deadline`, `status`)
- **POST** `/api/todos` (Membuat tugas baru)
  - Input JSON: `title`, `deadline`, `status` ('todo' atau 'done')
  - Response: `201 Created`
- **GET** `/api/todos/{id}` (Melihat detail satu tugas khusus)
  - Response: `200 OK` / `403 Forbidden` (Jika bukan miliknya)
- **PUT** `/api/todos/{id}` (Mengubah data/status tugas)
  - Input JSON: `title`, `deadline`, `status`
  - Response: `200 OK` / `403 Forbidden`
- **DELETE** `/api/todos/{id}` (Menghapus tugas)
  - Response: `200 OK` / `403 Forbidden`
