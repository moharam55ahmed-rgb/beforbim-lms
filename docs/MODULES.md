# Beforbim — Modular Monolith Modules Specification
**Architecture:** 26 Domain Modules + 1 Shared Core  
**Base Namespace:** `App\Modules\{ModuleName}`  

---

## 1. Modules Directory Overview

```text
app/Modules/
├── Auth/                   # M01: Multi-guard authentication, password resets, 2FA
├── User/                   # M02: User profiles, engineering bio, status management
├── AccessControl/          # M03: RBAC (Roles, Permissions, Gates)
├── Course/                 # M04: Course catalog, pricing, levels, approval workflow
├── Category/               # M05: Engineering hierarchy taxonomy
├── Curriculum/             # M06: Course sections, syllabus structure, module ordering
├── Lesson/                 # M07: Individual lessons, content gating, preview flags
├── Media/                  # M08: RVT/IFC/CAD attachments, video transcode, signed HLS
├── Enrollment/             # M09: Student course access, enrollment states, expiration
├── Cart/                   # M10: Shopping basket, multi-item checkout, coupons
├── Order/                  # M11: Order creation, invoicing, line item snapshots
├── Payment/                # M12: Gateway integration, payment records, webhooks
├── Transaction/            # M12b: Financial ledger, reconciliation, refund transactions
├── LiveClass/              # M13: Zoom & Google Meet scheduling, attendance
├── Assignment/             # M14: Project-based submissions, grading rubrics, BIM reviews
├── Task/                   # M15: Micro-tasks, daily engineering challenges
├── Assessment/             # M16: Quizzes, midterms, final timed exams
├── ExamSecurity/           # M17: Anti-cheat, configurable proctoring, event audit
├── DeviceSession/          # M18: Single active device enforcement, kick-out listener
├── AccountRestriction/     # M19: Suspicious activity flags, warning limits, auto-freeze
├── Progress/               # M20: Video watch time, lesson completion, milestone logic
├── Certificate/            # M21: Dynamic PDF generation, QR verification, public hashing
├── Notification/           # M22: In-app alerts, transactional emails, WhatsApp/SMS
├── Report/                 # M23: Financial, academic, completion analytics
├── Setting/                # M24: Platform branding, payment gateway keys, SEO CMS
├── AuditLog/               # M25: System-wide immutable administrative audit trail
├── SupportTicket/          # M26: Student technical/academic support & attachments
└── Shared/                 # Cross-cutting Contracts, Traits, Value Objects, DTOs
```

---

## 2. Granular Module Specifications

### Module 01: Auth (`App\Modules\Auth`)
- **Responsibilities:** Student/Instructor/Admin authentication, throttling, password recovery, session renewal.
- **Key Services:** `AuthenticationService`, `TwoFactorAuthService`, `PasswordResetService`.
- **Events Dispatched:** `UserLoggedInEvent`, `UserLoggedOutEvent`, `PasswordResetCompletedEvent`.

### Module 02: User (`App\Modules\User`)
- **Responsibilities:** User profile management, engineering credentials, avatar uploads, account status.
- **Key Services:** `UserProfileService`, `UserStatusManagementService`.
- **Policies:** `UserPolicy` (Self-update vs Admin update).

### Module 03: AccessControl (`App\Modules\AccessControl`)
- **Responsibilities:** Roles (`super_admin`, `admin`, `instructor`, `student`), permissions registry, authorization gates.
- **Key Services:** `RoleAssignmentService`, `PermissionRegistrarService`.

### Module 04: Course (`App\Modules\Course`)
- **Responsibilities:** Engineering course authoring, price management, prerequisite rules, publication workflow.
- **Extendability:** Designed with `purchasable` / `enrollable` contracts for future Bundles and Learning Paths.
- **States:** `DRAFT` ➔ `SUBMITTED` ➔ `UNDER_REVIEW` ➔ `APPROVED` / `REJECTED`.
- **Key Services:** `CourseManagementService`, `CoursePublishingService`.
- **Policies:** `CoursePolicy` (`update`: only owning instructor or admin; `publish`: admin only).
- **Events Dispatched:** `CourseSubmittedForReviewEvent`, `CoursePublishedEvent`.

### Module 05: Category (`App\Modules\Category`)
- **Responsibilities:** Multi-level taxonomy (e.g. *BIM Management*, *Structural BIM*, *Computational Design (Dynamo)*, *MEP Coordination*).
- **Key Services:** `CategoryTreeService`.

### Module 06: Curriculum (`App\Modules\Curriculum`)
- **Responsibilities:** Course syllabus, section breakdown, reordering via drag-and-drop.
- **Key Services:** `CurriculumOrderingService`.

### Module 07: Lesson (`App\Modules\Lesson`)
- **Responsibilities:** Individual lessons, lesson types (`VIDEO`, `DOCUMENT`, `QUIZ`, `ASSIGNMENT`), preview gating.
- **Policies:** `LessonPolicy` (`view`: checks enrollment in parent course or free preview flag).

### Module 08: Media (`App\Modules\Media`)
- **Responsibilities:** Engineering file asset security (protecting RVT, DWG, IFC files from unauthorized direct downloads), secure signed S3 streaming URLs for HLS video.
- **Realistic Video Protection:**
  - Tokenized short-lived signed URLs (60-second validity).
  - Floating semi-transparent dynamic canvas watermark displaying student details.
  - No unrealistic "impossible DRM" claims; acknowledged that hardware screen captures cannot be blocked client-side without OS kernel hooks.

### Module 09: Enrollment (`App\Modules\Enrollment`)
- **Responsibilities:** Core gatekeeper of student access.
- **Extendable Architecture:**
  - `enrollable_type` / `enrollable_id` polymorphic or clean enrollment source type (`COURSE`, `BUNDLE`, `LEARNING_PATH`, `SUBSCRIPTION`).
- **Separation Rule:** Never created directly on payment success; created exclusively by `EnrollmentWorkflowService` upon verified transaction.
- **Events Dispatched:** `StudentEnrolledEvent`, `EnrollmentExpiredEvent`.

### Module 10: Cart (`App\Modules\Cart`)
- **Responsibilities:** Student shopping basket, coupon validation against specific course IDs.
- **Key Services:** `CartService`, `CouponValidationService`.

### Module 11: Order (`App\Modules\Order`)
- **Responsibilities:** Order generation, line items snapshotting (immutable purchase price), invoicing.
- **Extendability:** `order_items` support `purchasable_type` and `purchasable_id`.
- **Events Dispatched:** `OrderCreatedEvent`, `OrderCancelledEvent`.

### Module 12: Payment & Transaction (`App\Modules\Payment`)
- **Responsibilities:** 
  - Strict separation: `Orders` ➔ `OrderItems` ➔ `Payments` ➔ `Transactions` ➔ `Enrollments`.
  - Payment records capture intent and payment gateway communication.
  - Transactions record finalized credit/debit movements with immutable reference numbers.
- **Key Services:** `PaymentGatewayResolver`, `ManualPaymentVerificationService`, `TransactionLedgerService`.
- **Events Dispatched:** `TransactionSettledEvent`, `PaymentRejectedEvent`.

### Module 13: LiveClass (`App\Modules\LiveClass`)
- **Responsibilities:** Zoom and Google Meet integration for cohort-based engineering workshops.
- **Key Services:** `ZoomMeetingService`, `AttendanceTrackingService`.

### Module 14: Assignment (`App\Modules\Assignment`)
- **Responsibilities:** BIM project task submission (e.g., submitting Revit `.rvt` structural models for instructor review), grading rubrics.
- **Key Services:** `AssignmentGradingService`.

### Module 15: Task (`App\Modules\Task`)
- **Responsibilities:** Daily practice tasks, quick BIM checklist milestones.

### Module 16: Assessment (`App\Modules\Assessment`)
- **Responsibilities:** Quizzes, midterms, final comprehensive engineering certification exams.
- **Key Services:** `AssessmentEngineService`, `AutoGradingService`.
- **Events Dispatched:** `AssessmentSubmittedEvent`, `AssessmentGradedEvent`.

### Module 17: ExamSecurity (`App\Modules\ExamSecurity`)
- **Responsibilities:** Configurable anti-cheat monitoring per exam.
- **Flexible Settings:**
  - `is_proctored_mode` (boolean, disabled by default for low-stakes quizzes).
  - `monitor_tab_switch` (boolean).
  - `monitor_fullscreen_exit` (boolean).
  - `max_violations_allowed` (integer).
  - `requires_manual_audit` (boolean: allows instructor/admin to review logged flags before certificate issuance).
  - No mandatory global webcam requirement.
- **Key Services:** `ExamTelemetryService`, `AntiCheatEnforcementService`.

### Module 18: DeviceSession (`App\Modules\DeviceSession`)
- **Responsibilities:** Single active device session enforcement.
- **Key Services:** `DeviceFingerprintService`, `SessionRevocationService`.
- **Middleware:** `EnforceSingleDeviceSessionMiddleware`.

### Module 19: AccountRestriction (`App\Modules\AccountRestriction`)
- **Responsibilities:** Warning issuance, automated temporary freezing for security violations.
- **Key Services:** `WarningEnforcementService`.

### Module 20: Progress (`App\Modules\Progress`)
- **Responsibilities:** Real-time video watch percentage, mandatory lesson completion sequencing.
- **Key Services:** `ProgressCalculationService`.
- **Events Dispatched:** `CourseCompletedEvent` (triggers certification).

### Module 21: Certificate (`App\Modules\Certificate`)
- **Responsibilities:** PDF certificate generation, dynamic template population, public QR verification.
- **Key Services:** `CertificateGeneratorService`, `CertificateVerificationService`.

### Module 22: Notification (`App\Modules\Notification`)
- **Responsibilities:** Multi-channel alerting (In-App notifications, Email templates, SMS).
- **Key Services:** `NotificationDispatchService`.

### Module 23: Report (`App\Modules\Report`)
- **Responsibilities:** Executive reports, instructor earnings reports, course drop-off analytics.
- **Key Services:** `FinancialReportService`, `AcademicAnalyticsService`.

### Module 24: Setting (`App\Modules\Setting`)
- **Responsibilities:** Global CMS configurations, payment keys, branding, localized hero banners.
- **Key Services:** `CmsSettingService`.

### Module 25: AuditLog (`App\Modules\AuditLog`)
- **Responsibilities:** Complete immutable tracking of all critical administrative, academic, and financial actions.
- **Tracked Domains:**
  - Admin & Staff Actions (role changes, system updates).
  - Instructor Actions (course creation, curriculum updates, grade submissions).
  - Payment Status Changes (gateway webhooks, offline manual approvals/rejections).
  - Enrollment Approvals, Suspensions, and Revocations.
  - Course Publishing Audits (submission, approval, rejection with feedback).
  - Security Actions (device session terminations, account restrictions, warnings).
- **Key Services:** `AuditLogService`, `AuditQueryService`.
- **Key Model:** `AuditLog`.

### Module 26: SupportTicket (`App\Modules\SupportTicket`)
- **Responsibilities:** Multi-tier academic and technical helpdesk.
- **Student Capabilities:**
  - Submit tickets categorized by domain (Technical, Course Content, Billing, Certificate).
  - Upload file attachments (error screenshots, PDF receipts, RVT sample).
  - View real-time ticket progress (`OPEN`, `IN_PROGRESS`, `WAITING_FOR_STUDENT`, `RESOLVED`, `CLOSED`).
- **Staff Capabilities:**
  - Assign tickets to specific instructors or academic support staff.
  - Internal private notes vs public student replies.
  - SLA tracking and status transitions.
- **Key Services:** `SupportTicketService`, `TicketAssignmentService`.
- **Key Models:** `SupportTicket`, `TicketMessage`, `TicketAttachment`.
