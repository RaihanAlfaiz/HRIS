# Employee Directory & HR Management System

## 📌 Project Overview

A lightweight HRIS tailored for HRD to manage, search, and store data for ~1500 employees. Focuses on fast search performance, strict data normalization, and clean UI/UX.

## 🛠 Tech Stack

- **Backend:** Laravel (Latest)
- **Database:** MySQL
- **Frontend:** Laravel Blade + Tailwind CSS (Alpine.js for lightweight reactivity)
- **Excel Export:** Maatwebsite/Laravel-Excel

## 📐 Database Architecture (Normalized DBML)

The database is strictly normalized to ensure performance. The AI MUST use the following DBML exact schema and relationships when creating Migrations and Models:

```dbml
// 1. Tabel Master & Auth
Table users {
  id bigint [primary key, increment]
  username varchar(50) [unique]
  password varchar(255)
}

Table departments {
  id bigint [primary key, increment]
  name varchar(100)
}

// 2. Tabel Inti (Ringan, khusus untuk pencarian & direktori)
Table employees {
  id bigint [primary key, increment]
  department_id bigint [ref: > departments.id]
  nip varchar(50) [unique]
  full_name varchar(150) [note: 'Di-index untuk search cepat']
  position varchar(100)
  employment_status varchar(50)
  join_date date
}

// 3. Tabel Detail Pribadi (Relasi 1-to-1)
Table employee_profiles {
  id bigint [primary key, increment]
  employee_id bigint [ref: - employees.id, unique]
  nik_ktp varchar(20) [unique]
  place_of_birth varchar(100)
  date_of_birth date
  gender varchar(10)
  religion varchar(50)
  marital_status varchar(50)
  blood_type varchar(5)
  address_ktp text
  address_domicile text
}

// 4. Tabel Kontak & Darurat (Relasi 1-to-1)
Table employee_contacts {
  id bigint [primary key, increment]
  employee_id bigint [ref: - employees.id, unique]
  email_work varchar(150) [unique]
  email_personal varchar(150)
  phone_number varchar(20)
  emergency_contact_name varchar(150)
  emergency_contact_phone varchar(20)
  emergency_contact_relation varchar(50)
}

// 5. Tabel Dokumen & Portofolio (Bisa 1-to-Many nantinya)
Table employee_documents {
  id bigint [primary key, increment]
  employee_id bigint [ref: > employees.id]
  document_type varchar(50) [note: 'Bisa diisi: Foto, CV, Portofolio URL, Portofolio PDF']
  file_path varchar(255) [null]
  url_link varchar(255) [null]
}

// 6. Tabel Finansial & Administrasi (Relasi 1-to-1)
Table employee_financials {
  id bigint [primary key, increment]
  employee_id bigint [ref: - employees.id, unique]
  npwp varchar(50) [null]
  bpjs_kesehatan varchar(50) [null]
  bpjs_ketenagakerjaan varchar(50) [null]
  bank_name varchar(50) [null]
  bank_account_number varchar(50) [null]
}
```
