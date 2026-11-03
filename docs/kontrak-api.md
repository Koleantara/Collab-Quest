# Kontrak API To-Do List

## 1. Autentikasi
- **POST** `/api/register`
  - Input JSON: `name`, `email`, `password`, `password_confirmation`
  - Response: `201 Created`
- **POST** `/api/login`
  - Input JSON: `email`, `password`
  - Response: `200 OK` + `token`
- **POST** `/api/logout` (Butuh Bearer Token)
  - Response: `200 OK`

## 2. Fitur CRUD To-Do List (Semua butuh Bearer Token)
- **GET** `/api/todos` (Melihat semua tugas milik user login)
  - Response: `200 OK` + Array Data (`id`, `title`, `deadline`, `status`)
- **POST** `/api/todos` (Membuat tugas baru)
  - Input JSON: `title`, `deadline`, `status` (todo/done)
  - Response: `201 Created`
- **PUT** `/api/todos/{id}` (Mengubah tugas)
  - Input JSON: `title`, `deadline`, `status`
  - Response: `200 OK`
- **DELETE** `/api/todos/{id}` (Menghapus tugas)
  - Response: `200 OK`
