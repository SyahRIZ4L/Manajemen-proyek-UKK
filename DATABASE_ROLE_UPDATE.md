# Database Role System Update

## Tanggal Update: November 6, 2025

### ✅ Perubahan yang Dilakukan:

## 1. Update Tabel `members`

### Role Column - SEBELUM:
```sql
ENUM('super admin', 'admin', 'member') DEFAULT 'member'
```

### Role Column - SESUDAH:
```sql
ENUM('Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member') DEFAULT 'Member'
```

## 2. Mapping Role Lama ke Baru:

| Role Lama | Role Baru |
|-----------|-----------|
| super admin | Project_Admin |
| admin | Team_Lead |
| member | Member |
| - | Developer (baru) |
| - | Designer (baru) |

## 3. File yang Diupdate:

### Migration Files:
1. **`database/migrations/2025_09_03_014317_create_project_members_table.php`**
   - Mengubah enum default dari `['super admin', 'admin', 'member']`
   - Menjadi `['Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member']`

2. **`database/migrations/2025_10_30_063836_modify_members_role_column.php`**
   - Mengubah migration untuk mengupdate ke role system yang baru
   - Down migration kembali ke enum lama untuk rollback support

### Controller Files:
1. **`app/Http/Controllers/TeamLeadController.php`**
   - Mengubah semua referensi dari `project_members` table → `members` table
   - Mengubah role check dari `'Team Lead'` → `'Team_Lead'`
   - Menambahkan API methods: `getStatistics()` dan `getProjects()`

### Route Files:
1. **`routes/web.php`**
   - Menambahkan Team Lead API routes:
     - `GET /api/teamlead/statistics`
     - `GET /api/teamlead/projects`

### Seeder Files:
1. **`database/seeders/UpdateRolesSeeder.php`** (NEW)
   - Seeder untuk mengupdate data existing dari role lama ke role baru
   - Mengubah struktur column dari enum lama ke enum baru

## 4. Sistem Role Baru:

### Project_Admin (Super Admin)
**Akses:** Full control semua project dan system
**Permissions:**
- Kelola semua proyek (create, edit, delete, archive)
- Full team management
- User management dan system settings
- Generate reports dan analytics
- Export data dan backup system

### Team_Lead
**Akses:** Manage project yang di-assign
**Permissions:**
- Assign tasks ke anggota tim
- Set prioritas tasks dan update status
- Monitor performa tim dan workload
- Generate team reports
- Koordinasi tim dan komunikasi

### Developer
**Akses:** Tasks development yang di-assign
**Permissions:**
- Kelola tasks development sendiri
- Code management (commit, PR, review)
- Bug tracking dan fixing
- Time logging dan technical documentation

### Designer
**Akses:** Design tasks yang di-assign
**Permissions:**
- Kelola design tasks
- Create mockups, wireframes, prototypes
- Upload design files dan assets
- Maintain brand guidelines
- User research dan UX design

### Member (Basic User)
**Akses:** View dan update tasks sendiri
**Permissions:**
- View dan update tasks yang assigned
- Basic time logging
- File download dan upload terbatas
- Komunikasi dasar (comments, messages)

## 5. Testing yang Perlu Dilakukan:

- [ ] Test login dengan user berbagai role
- [ ] Test Team Lead panel dengan user role Team_Lead
- [ ] Test project assignment ke Team Lead
- [ ] Test API endpoints:
  - `/api/teamlead/statistics`
  - `/api/teamlead/projects`
- [ ] Test member management dengan role baru
- [ ] Test permission system untuk setiap role

## 6. Cara Menjalankan Update:

```bash
# Jika fresh installation:
php artisan migrate:fresh --seed

# Jika update existing database:
php artisan db:seed --class=UpdateRolesSeeder
```

## 7. Rollback (jika diperlukan):

```bash
php artisan migrate:rollback --step=1
```

---

**Status:** ✅ **COMPLETED**
**Tested:** ⏳ **PENDING**
