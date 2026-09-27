# Beforbim — Phased Implementation Roadmap
**Platform:** Beforbim — Premium Engineering LMS  
**Release Target:** Production-Ready Enterprise Release  
**Methodology:** Incremental Modular Monolith Delivery with Zero Regression Gates  

---

## 1. Roadmap Milestones & Phases

```
[Phase 1] Architecture, Scaffolding & Foundational Docs (CURRENT)
    │
    ▼
[Phase 2] Domain Models, Schemas, Migrations & RBAC Seeding
    │
    ▼
[Phase 3] Anti-Piracy & Security Core (Device Sessions & Dynamic Watermarking)
    │
    ▼
[Phase 4] Commerce, Orders, Multi-Gateway Payments & Enrollment Engine
    │
    ▼
[Phase 5] Course Authoring, Curriculum, Protected Streaming & File Management
    │
    ▼
[Phase 6] Assessments, Quizzes, Timed Exams & Anti-Cheat Proctoring
    │
    ▼
[Phase 7] Live Classes, Assignments, Automated Progress & QR Certification
    │
    ▼
[Phase 8] Executive Reporting, Instructor Analytics & CMS Settings Engine
    │
    ▼
[Phase 9] Immutable Audit Logging, Support Helpdesk & Course Reviews/Announcements (COMPLETED)
```

---

## 2. Phase-by-Phase Breakdown

### Phase 1: Architecture & Structural Foundation (Completed in Current Sprint)
- [x] Analyze existing Laravel 13 & MySQL 8.4 environment.
- [x] Author comprehensive architecture documentation in `docs/`:
  - `PRD.md`
  - `ARCHITECTURE.md`
  - `DATABASE.md`
  - `ERD.md`
  - `MODULES.md`
  - `USER_FLOWS.md`
  - `DESIGN_SYSTEM.md`
  - `IMPLEMENTATION_PLAN.md`
- [x] Scaffold 24 modular domain folders under `app/Modules/` with standard internal subdirectories (`Controllers`, `Models`, `Services`, `Requests`, `Policies`, `Routes`, `Events`, `Jobs`, `Tests`).
- [x] Implement `ModuleServiceProvider` to automatically load module routes and services.

### Phase 2: Database Migrations, Eloquent Models & Access Control (Next Phase)
- Author database migration files for all 24 modules:
  - Users, Roles, Permissions, and `role_user` pivot.
  - `device_sessions`, `account_restrictions`, and `security_warnings`.
  - Categories, Courses, Sections, Lessons, Media contents, and Engineering resources.
  - Carts, Orders, Order items, Payments, and Enrollments.
  - Assessments, Questions, Attempts, and Proctoring logs.
  - Assignments, Submissions, Live classes, Certificates, and Notifications.
- Establish Eloquent Relationships, Query Scopes, and Invariants.
- Build comprehensive Database Seeders (`RolesAndPermissionsSeeder`, `DemoSuperAdminSeeder`, `BimCategoriesSeeder`).

### Phase 3: Anti-Piracy & Device Session Enforcement
- Implement `DeviceFingerprintService` (Hashing IP + Browser entropy).
- Build `EnforceSingleDeviceSessionMiddleware` with remote kick-out and event broadcast.
- Develop Blade/Alpine dynamic watermarked video component with bouncing canvas overlay.
- Build `SecurityAuditLogService` for persistent administrative traceability.

### Phase 4: Commerce, Payments & Strict Enrollment
- Build shopping cart service supporting single-course checkout.
- Implement checkout pipeline and database transaction wrapping.
- Implement `PaymentGatewayResolver`:
  - Online webhook handlers (Mada, Credit Card, Tabby, Tamara) with HMAC signature verification.
  - Offline bank wire transfer proof upload and Admin review workflow.
- Bind `PaymentVerifiedEvent` to `GrantCourseEnrollmentListener` (ensuring 0% unverified access).

### Phase 5: Course Curriculum & Protected Asset Streaming
- Implement Instructor course authoring studio:
  - Drag-and-drop section and lesson ordering.
  - Upload handling for large engineering attachments (`.rvt`, `.dwg`, `.ifc`, `.pdf`).
- Admin Course Quality Audit & Approval flow (`Draft` ➔ `Submitted` ➔ `Approved`).
- Signed short-lived HLS video streaming controller.

### Phase 6: Assessments & Anti-Cheat Proctoring Engine
- Timed quiz and exam execution engine with server-side synchronized countdown.
- Randomized question shuffling and option permuting.
- Frontend telemetry client (blur, tab-switch, fullscreen exit) reporting to `ExamSecurityController`.
- Auto-submission on violation limit exceeded.

### Phase 7: Live Classes, Assignments & QR Certification
- Zoom / Google Meet integration with scheduled course calendar.
- Assignment submission and rubric grading module.
- 100% curriculum completion listener triggering PDF certificate generation with unique UUID and public QR code verification page.

### Phase 8: Reporting, Instructor Analytics & CMS Settings (Completed)
- [x] Executive revenue reports, financial ledger reconciliation, and CSV export.
- [x] Instructor earnings reports and payout calculation based on configured platform commission rates.
- [x] Academic performance analytics and learner progression drop-off funnels (0-25%, 26-50%, 51-75%, 76-99%, 100%).
- [x] CMS settings engine with typed resolution and cache layer (`general`, `branding`, `payment`, `security`).

### Phase 9: Immutable Audit Logging, Support Helpdesk & Community Modules (Completed)
- [x] Immutable administrative audit trail (`audit_logs`) tracking actor, target entity, request IP, user-agent, and JSON values diff.
- [x] Full helpdesk support ticket workflow (`support_tickets`, `ticket_messages`, `ticket_attachments`) with auto ticket numbering (`TCK-YYYY-XXXXXX`), file uploads, staff assignment, and private internal notes.
- [x] Course reviews and ratings system (`course_reviews`) restricted to enrolled students with admin moderation workflow.
- [x] Instructor course announcements module (`course_announcements`) with automatic notification broadcast to active enrolled cohorts.
- [x] Comprehensive test coverage with 77/77 passing Feature and Unit tests and zero regressions.

---

## 3. Quality Assurance & Testing Gates

Every module must meet the following test coverage criteria before entering production:
1. **Unit Tests:** All core business services (e.g., `PaymentService`, `EnrollmentAccessService`, `DeviceFingerprintService`) must achieve >90% code coverage.
2. **Feature Tests:** HTTP endpoints tested for:
   - Positive authentication & authorization flows.
   - Negative tests: Unauthorized cross-instructor edits, expired enrollments accessing video streams, unapproved payments attempting lesson playback.
3. **Database Integrity Tests:** Transactions tested under simulated concurrent checkouts to ensure no duplicate enrollments.
