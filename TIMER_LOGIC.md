# Timer Logic per Card

## Overview
Setiap card memiliki timer-nya masing-masing yang akan melacak waktu pengerjaan aktual. Timer akan otomatis dikelola berdasarkan status card.

## State Diagram

```
┌─────────┐  Start Work    ┌──────────────┐  Submit       ┌────────┐
│  TODO   │───────────────>│ IN_PROGRESS  │──────────────>│ REVIEW │
└─────────┘    (START)      └──────────────┘    (PAUSE)    └────────┘
                                    ^                            │
                                    │        Reject              │
                                    └────────────────────────────┘
                                         (RESUME)                │
                                                                 │ Approve
                                                                 v
                                                            ┌────────┐
                                                            │  DONE  │
                                                            └────────┘
                                                             (STOP)
```

## Timer Behavior

### 1. START Timer
**Trigger:** Status berubah dari `todo` → `in_progress`
- Timer mulai menghitung untuk pertama kali
- Timestamp `started_at` dicatat
- TimeLog baru dibuat dengan `start_time` = now()
- Card `is_timer_active` = true

**Contoh:**
```
Member klik "Start Work" → Status: todo → in_progress
✅ Timer START menghitung
```

### 2. PAUSE Timer
**Trigger:** Status berubah dari `in_progress` → `review`
- Timer di-pause (berhenti sementara)
- TimeLog di-update: `end_time` = now()
- Duration dihitung dan disimpan
- Card `is_timer_active` = false
- Total waktu tetap tersimpan

**Contoh:**
```
Member klik "Submit for Review" → Status: in_progress → review
⏸️ Timer PAUSE (waktu: 2 jam 30 menit)
```

### 3. RESUME Timer
**Trigger:** Status berubah dari `review` → `in_progress` (Card direject Team Lead)
- Timer melanjutkan hitungan
- TimeLog baru dibuat untuk melanjutkan tracking
- Card `is_timer_active` = true
- Waktu sebelumnya tetap tersimpan

**Contoh:**
```
Team Lead reject card → Status: review → in_progress
▶️ Timer RESUME (lanjut dari 2 jam 30 menit)
Member lanjut mengerjakan 1 jam 15 menit lagi
Total waktu: 3 jam 45 menit
```

### 4. STOP Timer (Permanent)
**Trigger:** Status berubah menjadi `done`
- Timer berhenti permanen
- TimeLog terakhir ditutup
- Total waktu final dihitung
- Card `is_timer_active` = false
- Card `completed_at` dicatat
- Card `actual_hours` berisi total waktu pengerjaan

**Contoh:**
```
Team Lead approve card → Status: review → done
⏹️ Timer STOP PERMANEN (total: 3 jam 45 menit)
```

## Implementation Details

### Database: TimeLog Table
```
- log_id (PK)
- user_id (FK)
- card_id (FK)
- start_time (timestamp)
- end_time (timestamp, nullable)
- duration_minutes (int, calculated)
- description (text)
```

### Card Columns
```
- is_timer_active (boolean)
- timer_started_at (timestamp, nullable)
- actual_hours (decimal, calculated from sum of TimeLogs)
- started_at (timestamp)
- completed_at (timestamp, nullable)
```

## Code Flow

### MemberController::updateCardStatus()

```php
if (todo → in_progress) {
    autoStartTimer()  // START
}

if (in_progress → review) {
    autoStopTimer()   // PAUSE
}

if (review → in_progress) {
    autoStartTimer()  // RESUME
}

if (→ done) {
    autoStopTimer()   // STOP PERMANENT
}
```

### Card Model::handleStatusChange()

```php
shouldStartAutoTimer():
  - todo → in_progress (START)
  - review → in_progress (RESUME)

shouldStopAutoTimer():
  - → review (PAUSE)
  - → done (STOP)
  - → todo (RESET)
```

## Example Scenarios

### Scenario 1: Happy Path (No Rejection)
```
1. todo → in_progress (START) - Timer: 0:00
2. Member bekerja 3 jam
3. in_progress → review (PAUSE) - Timer: 3:00 (paused)
4. review → done (STOP) - Timer: 3:00 (final)
```

### Scenario 2: Single Rejection
```
1. todo → in_progress (START) - Timer: 0:00
2. Member bekerja 2 jam
3. in_progress → review (PAUSE) - Timer: 2:00 (paused)
4. review → in_progress (RESUME) - Timer: 2:00 (running)
5. Member bekerja 1 jam lagi
6. in_progress → review (PAUSE) - Timer: 3:00 (paused)
7. review → done (STOP) - Timer: 3:00 (final)
```

### Scenario 3: Multiple Rejections
```
1. todo → in_progress (START) - Timer: 0:00
2. Member bekerja 1 jam
3. in_progress → review (PAUSE) - Timer: 1:00 (paused)
4. review → in_progress (RESUME) - Timer: 1:00 (running)
5. Member bekerja 30 menit
6. in_progress → review (PAUSE) - Timer: 1:30 (paused)
7. review → in_progress (RESUME) - Timer: 1:30 (running)
8. Member bekerja 45 menit
9. in_progress → review (PAUSE) - Timer: 2:15 (paused)
10. review → done (STOP) - Timer: 2:15 (final)
```

## Benefits

✅ **Akurat:** Timer hanya menghitung waktu kerja aktual, tidak termasuk waktu review
✅ **Otomatis:** Tidak perlu manual start/stop, otomatis mengikuti status card
✅ **Transparan:** Team Lead bisa lihat total waktu pengerjaan
✅ **Audit Trail:** Semua TimeLog tersimpan untuk tracking detail
✅ **Fair:** Member tidak kena hitung waktu saat card dalam review

## API Response

```json
{
  "success": true,
  "message": "Card status updated successfully.",
  "status": "review",
  "timer_started": false,
  "timer_paused": true,
  "timer_resumed": false,
  "timer_stopped": false
}
```
