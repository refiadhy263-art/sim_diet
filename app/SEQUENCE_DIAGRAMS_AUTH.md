# SIM_DIET - Authentication Flow Diagram

## Sequence Diagram: Login Process

```mermaid
sequenceDiagram
    participant User as Pengguna
    participant Browser as Browser/Frontend
    participant LoginController as Login Controller
    participant UsersModel as Users Model
    participant Database as Database
    participant Session as Session Manager

    User->>Browser: Klik tombol Login
    User->>Browser: Isi username & password
    Browser->>LoginController: POST /login (form submission)
    
    LoginController->>LoginController: Validasi input<br/>(required, min_length)
    
    alt Validasi Gagal
        LoginController-->>Browser: JSON Error dengan pesan validasi
        Browser->>User: Tampilkan error message
    else Validasi Sukses
        LoginController->>UsersModel: getByUsername($username)
        UsersModel->>Database: SELECT * FROM users WHERE username = ?
        Database-->>UsersModel: User data atau null
        
        alt User Tidak Ditemukan
            LoginController-->>Browser: JSON Error "Username salah"
            Browser->>User: Tampilkan error message
        else User Ditemukan
            LoginController->>LoginController: password_verify($password, $hash)
            
            alt Password Salah
                LoginController-->>Browser: JSON Error "Password salah"
                Browser->>User: Tampilkan error message
            else Password Benar
                LoginController->>Session: Ambil id_bangsal dari users
                LoginController->>Session: session()->set([<br/>  'id_users' => $user['id_users'],<br/>  'username' => $user['username'],<br/>  'role' => $user['role'],<br/>  'id_bangsal' => $id_bangsal,<br/>  'logged_in' => true<br/>])
                Session-->>LoginController: Session data tersimpan
                
                LoginController->>LoginController: Buat log aktivitas
                
                LoginController-->>Browser: JSON Success dengan redirect URL
                Browser->>User: Redirect ke /dashboard
                Browser->>LoginController: GET /dashboard
                LoginController->>LoginController: Cek session('logged_in')
                LoginController-->>Browser: Render dashboard view
                Browser->>User: Tampilkan dashboard
            end
        end
    end
```

## Sequence Diagram: Protected Route Access

```mermaid
sequenceDiagram
    participant User as Pengguna
    participant Browser as Browser
    participant AuthFilter as Authenticate Filter
    participant Router as Router
    participant Controller as Controller
    participant Session as Session

    User->>Browser: Akses /pasien/getData
    Browser->>Router: GET /pasien/getData
    Router->>AuthFilter: Jalankan filter 'authenticate'
    
    AuthFilter->>Session: Cek session('logged_in')
    
    alt Belum Login
        Session-->>AuthFilter: false / tidak ada
        AuthFilter-->>Router: Redirect ke login
        Router-->>Browser: 302 Redirect ke /
        Browser->>User: Tampilkan halaman login
    else Sudah Login
        Session-->>AuthFilter: true
        AuthFilter->>AuthFilter: Session data valid
        AuthFilter-->>Router: Lanjutkan
        Router->>Controller: Jalankan metode controller
        
        Controller->>Session: Ambil id_bangsal = session('id_bangsal')
        Controller->>Controller: Query data dengan filter bangsal
        
        Controller-->>Browser: JSON response dengan data
        Browser->>User: Tampilkan data di halaman
    end
```

## Sequence Diagram: Logout Process

```mermaid
sequenceDiagram
    participant User as Pengguna
    participant Browser as Browser
    participant LogoutController as Logout Controller
    participant Session as Session Manager
    participant Router as Router

    User->>Browser: Klik tombol Logout
    Browser->>LogoutController: GET /logout
    
    LogoutController->>Session: session()->destroy()
    Session-->>LogoutController: Session dihapus
    
    LogoutController->>LogoutController: Buat log aktivitas logout
    
    LogoutController-->>Router: Redirect ke home
    Router-->>Browser: 302 Redirect ke /
    Browser->>User: Tampilkan halaman login/home
```

## Sequence Diagram: Ward-Based Access Control

```mermaid
sequenceDiagram
    participant Nurse1 as Perawat Bangsal A
    participant Nurse2 as Perawat Bangsal B
    participant Browser1 as Browser Nurse A
    participant Browser2 as Browser Nurse B
    participant Controller as Pasien Controller
    participant Model as Pasien Model
    participant Database as Database

    Nurse1->>Browser1: Login sebagai perawat bangsal A
    Nurse1->>Browser1: View /pasien/getData
    Browser1->>Controller: GET /pasien/getData (session id_bangsal=1)
    
    Controller->>Model: getAll(search, id_bangsal=1)
    Model->>Database: SELECT * FROM pasien<br/>WHERE id_bangsal = 1
    Database-->>Model: Data pasien bangsal A
    Model-->>Controller: Result
    Controller-->>Browser1: JSON data pasien bangsal A saja
    Browser1->>Nurse1: Tampilkan daftar pasien bangsal A
    
    par Parallel Request
        Nurse2->>Browser2: Login sebagai perawat bangsal B
        Nurse2->>Browser2: View /pasien/getData
        Browser2->>Controller: GET /pasien/getData (session id_bangsal=2)
        
        Controller->>Model: getAll(search, id_bangsal=2)
        Model->>Database: SELECT * FROM pasien<br/>WHERE id_bangsal = 2
        Database-->>Model: Data pasien bangsal B
        Model-->>Controller: Result
        Controller-->>Browser2: JSON data pasien bangsal B saja
        Browser2->>Nurse2: Tampilkan daftar pasien bangsal B
    end

    Note over Nurse1,Database: Keamanan: Setiap perawat<br/>hanya melihat data bangsal mereka
```

## Sequence Diagram: Failed Authentication Security

```mermaid
sequenceDiagram
    participant Attacker as Penyerang
    participant Browser as Browser
    participant LoginController as Login Controller
    participant Database as Database

    Attacker->>Browser: Coba akses /pasien/getData tanpa login
    Browser->>LoginController: GET /pasien/getData
    
    LoginController->>LoginController: Cek session('logged_in')
    
    alt Session Tidak Valid
        LoginController-->>Browser: 302 Redirect ke /
        Browser->>Attacker: Tampilkan halaman login
        Attacker->>Browser: Tidak bisa akses data
    end

    Attacker->>Browser: Coba direct SQL injection di form login
    Browser->>LoginController: POST /login dengan input berbahaya
    
    LoginController->>LoginController: Validasi & sanitasi input
    LoginController->>Database: Gunakan parameterized query
    Database-->>LoginController: Query aman
    LoginController-->>Browser: Invalid credentials message
    Browser->>Attacker: Tidak berhasil
```

## Catatan Keamanan (Security Notes)

### Key Security Points:
1. **Password Hashing**: `password_hash()` saat registrasi, `password_verify()` saat login
2. **Session Management**: Data sensitif disimpan di server session, bukan cookie
3. **Ward Isolation**: `id_bangsal` di session digunakan untuk filter semua query
4. **CSRF Protection**: CodeIgniter built-in CSRF token
5. **Input Validation**: Server-side validation di controller sebelum database query
6. **Prepared Statements**: Query builder CodeIgniter mencegah SQL injection

### Flow Identifikasi:
- ✅ Login → Password Verify → Session Set → Redirect
- ✅ Protected Route → Check Session → Filter by Ward
- ✅ Logout → Destroy Session → Redirect to Login
- ❌ No Session → Redirect to Login
- ❌ Wrong Password → Error Message (generic)
- ❌ Invalid User → Error Message (generic)
