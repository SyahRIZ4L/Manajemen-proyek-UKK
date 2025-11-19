# Team Lead Todo Feature Update

## Deskripsi
Fitur todolist telah diperbarui untuk memberikan akses penuh kepada Team Lead untuk mengelola todo items pada card detail.

## Perubahan yang Dilakukan

### 1. UI Update - Team Lead Panel (`resources/views/teamlead/panel.blade.php`)

#### Todo List Card Header
- Ditambahkan tombol **"Add"** untuk membuat todo baru
- Header kini memiliki layout flex untuk menampilkan judul dan tombol

#### Add Todo Form
- Form input tersembunyi yang muncul ketika tombol "Add" diklik
- Input field dengan enter key support untuk menambahkan todo
- Tombol submit (✓) dan cancel (✗)

#### Todo List Items
- Setiap todo item kini menampilkan:
  - Checkbox yang bisa diklik untuk toggle completed status
  - Teks todo dengan strikethrough otomatis jika completed
  - Informasi pembuat (username) dan tanggal pembuatan
  - Tombol **Edit** (ikon pensil) untuk mengubah teks todo
  - Tombol **Delete** (ikon trash) untuk menghapus todo

### 2. JavaScript Functions

#### Variabel Global
- `currentCardIdForTodos`: Menyimpan ID card yang sedang aktif untuk operasi todo

#### Fungsi Manajemen Todo
1. **showAddTodoForm()**: Menampilkan form input todo baru
2. **hideAddTodoForm()**: Menyembunyikan form input todo
3. **addTeamLeadTodo()**: Menambahkan todo baru via API
4. **toggleTeamLeadTodo(todoId)**: Toggle status completed todo
5. **editTeamLeadTodo(todoId, currentText)**: Edit teks todo menggunakan prompt
6. **deleteTeamLeadTodo(todoId)**: Hapus todo dengan konfirmasi

#### Update Fungsi Existing
- **displayTeamLeadTodos()**: Diperbarui untuk menampilkan checkbox aktif dan tombol aksi
- **loadCardDetails()**: Sekarang menyimpan `currentCardIdForTodos` saat card detail dibuka

### 3. Backend - TodoController
Controller sudah mendukung Team Lead dengan baik:
- Method `checkCardAccess()` memverifikasi akses Team Lead melalui ProjectMember
- Mendukung format role 'Team Lead' dan 'Team_Lead'
- Eager loading relationships untuk performa optimal

## Fitur yang Tersedia untuk Team Lead

### ✅ Menambahkan Todo
1. Klik tombol **"Add"** di Todo List card
2. Ketik teks todo dan tekan Enter atau klik tombol ✓
3. Todo baru akan muncul di list

### ✅ Toggle Todo (Complete/Incomplete)
- Klik checkbox di sebelah todo item
- Todo yang completed akan otomatis ter-strikethrough

### ✅ Edit Todo
1. Klik tombol **Edit** (ikon pensil)
2. Ubah teks di prompt dialog
3. Klik OK untuk menyimpan

### ✅ Hapus Todo
1. Klik tombol **Delete** (ikon trash)
2. Konfirmasi penghapusan
3. Todo akan dihapus dari list

## Access Control
- **Admin**: Akses penuh ke semua card todos
- **Team Lead**: Akses ke todos pada card dalam project mereka
- **Member**: Akses ke todos pada card yang di-assign kepada mereka

## Notifikasi
Sistem notifikasi menampilkan pesan sukses untuk:
- Todo berhasil ditambahkan
- Todo berhasil diupdate
- Todo berhasil dihapus

## API Endpoints yang Digunakan
- `GET /api/card-todos?card_id={id}` - Ambil semua todos untuk card
- `POST /api/card-todos` - Buat todo baru
- `PUT /api/card-todos/{todoId}` - Update teks todo
- `PUT /api/card-todos/{todoId}/toggle` - Toggle completed status
- `DELETE /api/card-todos/{todoId}` - Hapus todo

## Catatan Teknis
- CSRF token digunakan pada semua request POST/PUT/DELETE
- Error handling dengan alert dialog untuk user feedback
- Loading spinner ditampilkan saat fetch data todos
- Escape HTML untuk mencegah XSS attacks
- Responsive design dengan Bootstrap 5
