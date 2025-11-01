# Permission System Documentation

## Overview
Sistem permission telah berhasil diperbarui dengan role-based access control yang lebih spesifik dan terstruktur.

## Role-Specific Permissions

### 1. Project_Admin
**Fitur Utama:**
- Kelola semua proyek (create, edit, delete, archive)
- Full team management (add, remove, change roles)
- Complete task management untuk semua tasks
- Generate reports dan analytics
- Export data dan backup system
- Access ke semua data proyek
- User management dan system settings

**Permissions:**
- `create_project`, `edit_project`, `delete_project`, `archive_project`
- `view_all_projects`, `add_team_members`, `remove_team_members`
- `change_member_roles`, `view_all_members`
- `create_tasks`, `edit_all_tasks`, `delete_tasks`
- `assign_tasks`, `change_task_priority`, `change_task_status`
- `view_all_tasks`, `generate_reports`, `view_analytics`
- `export_data`, `view_time_tracking`, `manage_settings`
- `backup_restore`, `user_management`

### 2. Team_Lead
**Fitur Utama:**
- Assign tasks ke anggota tim
- Set prioritas tasks dan update status
- Monitor performa tim dan workload
- Koordinasi tim dan komunikasi
- Generate team reports
- Review task completion

**Permissions:**
- `view_assigned_projects`, `edit_project_details`
- `view_team_members`, `coordinate_team`
- `assign_tasks_to_team`, `set_task_priority`, `update_task_status`
- `view_team_progress`, `create_team_tasks`, `edit_team_tasks`
- `review_task_completion`, `monitor_team_performance`
- `view_team_workload`, `track_team_deadlines`
- `send_team_notifications`, `create_team_meetings`
- `generate_team_reports`, `view_team_analytics`

### 3. Developer
**Fitur Utama:**
- Kelola tasks development yang assigned
- Code management (commit, PR, review)
- Bug tracking dan fixing
- Time logging dan technical documentation
- Update status tasks sendiri

**Permissions:**
- `view_assigned_tasks`, `update_own_task_status`
- `add_task_comments`, `upload_task_attachments`, `log_work_time`
- `commit_code`, `create_pull_requests`, `review_code`, `manage_branches`
- `create_technical_docs`, `update_api_docs`
- `report_bugs`, `fix_bugs`, `test_features`
- `participate_in_meetings`, `ask_technical_questions`

### 4. Designer
**Fitur Utama:**
- Kelola design tasks
- Create mockups, wireframes, prototypes
- Upload design files dan assets
- Maintain brand guidelines
- User research dan UX design

**Permissions:**
- `view_assigned_tasks`, `update_own_task_status`
- `add_task_comments`, `upload_design_files`, `log_work_time`
- `create_mockups`, `design_wireframes`, `create_prototypes`
- `manage_design_assets`, `review_design_feedback`
- `iterate_designs`, `approve_design_changes`
- `maintain_brand_guidelines`, `create_style_guides`
- `conduct_user_research`, `create_user_personas`, `design_user_flows`
- `participate_in_design_reviews`, `present_designs`

### 5. Member (Basic User)
**Fitur Utama:**
- View dan update tasks yang assigned
- Basic time logging
- File download dan upload terbatas
- Komunikasi dasar (comments, messages)

**Permissions:**
- `view_own_tasks`, `update_own_task_status`
- `add_task_comments`, `view_task_details`
- `log_own_time`, `view_own_timesheet`
- `participate_in_meetings`, `send_messages`, `receive_notifications`
- `download_files`, `upload_own_files`
- `edit_own_profile`, `change_own_password`

## Controllers Created

### 1. TeamLeadController
- **Route Prefix**: `/teamlead`
- **Key Features**: Task assignment, team coordination, status updates
- **Main Methods**: dashboard(), tasks(), assignTask(), updateTaskStatus()

### 2. DeveloperController  
- **Route Prefix**: `/developer`
- **Key Features**: Code management, bug tracking, technical tasks
- **Main Methods**: dashboard(), myTasks(), logWorkTime(), bugReports()

### 3. DesignerController
- **Route Prefix**: `/designer` 
- **Key Features**: Design workflow, asset management, UX research
- **Main Methods**: dashboard(), portfolio(), brandGuidelines(), userResearch()

### 4. MemberController
- **Route Prefix**: `/member`
- **Key Features**: Basic task management, timesheet, file handling
- **Main Methods**: dashboard(), myTasks(), timesheet(), downloadFile()

### 5. AdminController (Updated)
- **Route Prefix**: `/admin`
- **Key Features**: Full system management, reporting, user management
- **Main Methods**: dashboard(), projects(), members(), reports()

## Permission Middleware

### CheckPermission Middleware
- **Path**: `app/Http/Middleware/CheckPermission.php`
- **Key Features**:
  - Role-based permission mapping
  - Static permission checking methods
  - Role display names dan feature descriptions
  - Centralized permission management

## Routes Structure

```php
// Role-specific route groups dengan middleware protection
Route::prefix('admin')->name('admin.')->group(function () {
    // Project Admin routes
});

Route::prefix('teamlead')->name('teamlead.')->group(function () {
    // Team Lead routes  
});

Route::prefix('developer')->name('developer.')->group(function () {
    // Developer routes
});

Route::prefix('designer')->name('designer.')->group(function () {
    // Designer routes
});

Route::prefix('member')->name('member.')->group(function () {
    // Member routes
});
```

## Role-Based Dashboard Redirection

HomeController sekarang automatically redirect users ke dashboard yang sesuai dengan role mereka:

```php
return match($userRole) {
    'Project_Admin' => redirect()->route('admin.dashboard'),
    'Team_Lead' => redirect()->route('teamlead.dashboard'),
    'Developer' => redirect()->route('developer.dashboard'),
    'Designer' => redirect()->route('designer.dashboard'),
    'member' => redirect()->route('member.dashboard'),
    default => $this->showGeneralDashboard($user)
};
```

## Key Features Implemented

1. **Granular Permissions**: Setiap role memiliki set permission yang spesifik
2. **Role Separation**: Clear separation of concerns antar role
3. **Security**: Permission checks di setiap sensitive operation
4. **Scalability**: Easy to add new roles atau modify permissions
5. **User Experience**: Role-specific dashboards dengan fitur yang relevan
6. **Validation**: Comprehensive validation untuk setiap action

## Next Steps for Testing

1. Test login dengan different role accounts
2. Verify permission checks berfungsi dengan baik
3. Test role-specific features dan restrictions
4. Validate dashboard redirections
5. Check error handling untuk unauthorized access

## Test Users Available

Gunakan test users yang sudah dibuat untuk testing:
- `admin@test.com` (Project_Admin)
- `teamlead@test.com` (Team_Lead)  
- `developer@test.com` (Developer)
- `designer@test.com` (Designer)
- `member@test.com` (member)

Password untuk semua: `password123`
