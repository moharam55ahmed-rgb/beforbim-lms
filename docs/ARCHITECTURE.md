# Beforbim — System Architecture Document
**Architecture Pattern:** Modular Monolith (Domain-Centric)  
**Framework:** Laravel 13.x  
**Runtime:** PHP 8.3 (Laragon Local / Nginx & PHP-FPM Production)  
**Database:** MySQL 8.4 (InnoDB, Strict SQL Mode)  
**Frontend Engine:** Blade + Tailwind CSS v4 + Alpine.js + Vite  

---

## 1. Architectural Philosophy & Principles

Beforbim is structured as a **Modular Monolith**. This pattern provides the agility and simplified transactional boundaries of a single repository and unified database, while enforcing the strict boundaries, maintainability, and domain isolation of a microservices architecture.

```
+-----------------------------------------------------------------------------------+
|                                PRESENTATION LAYER                                 |
|      Blade Views (RTL First)  |  Tailwind CSS v4  |  Alpine.js Reactive Islands   |
+-----------------------------------------------------------------------------------+
                                         │ HTTP / JSON
                                         ▼
+-----------------------------------------------------------------------------------+
|                              MODULAR MONOLITH CORE                                |
|                                                                                   |
|  +--------------------+  +--------------------+  +--------------------+           |
|  |     M01: Auth      |  |     M02: User      |  |     M03: RBAC      |           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  |    M04: Course     |  |   M06: Curriculum  |  |    M07: Lesson     |           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  |   M09: Enrollment  |  |    M11: Order      |  |    M12: Payment    |           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  +--------------------+  +--------------------+  +--------------------+           |
|  | M16: Quizzes/Exams |  | M17: Exam Security |  | M18: DeviceSession |           |
|  +--------------------+  +--------------------+  +--------------------+           |
|                                                                                   |
|  Event Bus / Domain Events (Illuminate\Contracts\Events\Dispatcher)               |
+-----------------------------------------------------------------------------------+
                                         │
                                         ▼
+-----------------------------------------------------------------------------------+
|                        APPLICATION SERVICES & DOMAIN LAYER                        |
|   Business Services  |  Form Requests  |  Policies & Gates  |  Queued Jobs        |
+-----------------------------------------------------------------------------------+
                                         │
                                         ▼
+-----------------------------------------------------------------------------------+
|                           INFRASTRUCTURE & PERSISTENCE                            |
|    MySQL 8.4 (InnoDB)  |  Redis (Sessions & Cache)  |  Object Storage (S3 / R2)   |
+-----------------------------------------------------------------------------------+
```

### Key Principles Applied:
1. **Single Responsibility Principle (SRP):** Controllers only orchestrate HTTP requests, delegates work to dedicated Domain Services. Models are pure Eloquent entities with business scopes and domain invariants.
2. **Open / Closed Principle (OCP):** Payment gateways, video stream providers, and live meeting integrations implement formal PHP interfaces (e.g. `PaymentGatewayInterface`, `VideoStreamingServiceInterface`), allowing new providers to be plugged in without mutating core checkout logic.
3. **Loose Coupling via Domain Events:** Cross-module state transitions are mediated by Laravel Events. For instance, when `PaymentService` confirms a successful charge, it dispatches `PaymentCompletedEvent`. The `Enrollment` module listens and executes course activation without `Payment` module directly depending on enrollment internal services.
4. **Dependency Inversion Principle (DIP):** High-level business policies do not depend on low-level infrastructure details; both depend on domain contracts located in `App\Modules\Shared\Contracts`.

---

## 2. Directory Structure of the Modular Monolith

All business modules reside under `app/Modules/`. Each module is self-contained:

```text
app/
├── Modules/
│   ├── Auth/
│   ├── User/
│   ├── AccessControl/
│   ├── Course/
│   ├── Category/
│   ├── Curriculum/
│   ├── Lesson/
│   ├── Media/
│   ├── Enrollment/
│   ├── Cart/
│   ├── Order/
│   ├── Payment/
│   ├── LiveClass/
│   ├── Assignment/
│   ├── Task/
│   ├── Assessment/
│   ├── ExamSecurity/
│   ├── DeviceSession/
│   ├── AccountRestriction/
│   ├── Progress/
│   ├── Certificate/
│   ├── Notification/
│   ├── Report/
│   ├── Setting/
│   └── Shared/                # Common traits, base DTOs, contracts, value objects
│
└── Providers/
    ├── AppServiceProvider.php
    └── ModuleServiceProvider.php # Auto-registers routes, policies, and providers
```

### Internal Anatomy of a Single Module (e.g., `Course`):
```text
app/Modules/Course/
├── Controllers/
│   ├── AdminCourseController.php
│   ├── InstructorCourseController.php
│   └── PublicCourseController.php
├── Models/
│   ├── Course.php
│   ├── CourseRequirement.php
│   └── CourseOutcome.php
├── Services/
│   ├── CourseManagementService.php
│   ├── CoursePublishingWorkflowService.php
│   └── CoursePricingService.php
├── Requests/
│   ├── CreateCourseRequest.php
│   └── UpdateCourseRequest.php
├── Policies/
│   └── CoursePolicy.php
├── Routes/
│   ├── web.php
│   └── api.php
├── Events/
│   ├── CourseSubmittedForReviewEvent.php
│   └── CourseApprovedEvent.php
├── Jobs/
│   └── ProcessCoursePromotionalVideoJob.php
└── Tests/
    ├── Feature/
    └── Unit/
```

---

## 3. Communication Patterns Between Modules

To prevent a "distributed ball of mud":

| Communication Type | Mechanism | Example Scenario |
| :--- | :--- | :--- |
| **Synchronous Query** | Public Service Contracts / Facades | `EnrollmentService::isUserEnrolled($userId, $courseId)` called inside `LessonPolicy`. |
| **Asynchronous Command** | Laravel Queued Jobs (`ShouldQueue`) | Transcoding video files or generating dynamic PDF certificates via `GenerateCertificatePdfJob`. |
| **State Change Broadcast** | Domain Events (`dispatch(new Event())`) | `OrderPaidEvent` handled by `GrantCourseEnrollmentListener` and `SendOrderInvoiceNotificationListener`. |

---

## 4. Anti-Piracy & Content Security Pipeline

Beforbim enforces an industry-leading security posture tailored for premium technical engineering training:

```
[Student Request] ──► [DeviceSessionMiddleware] (Validate Single Active Session)
                               │
                               ├─► Session Valid? ──► Continue
                               └─► Session Expired/Stolen? ──► Force Logout & Terminate
                               │
                      [CoursePolicy / LessonPolicy]
                               │
                               ├─► Valid Enrollment? ──► Generate Signed Short-Lived Token (60s)
                               └─► No Active Enrollment ──► 403 Forbidden
                               │
                      [Streaming Player]
                               │
                               ├─► Token Verified via Signed URL Endpoint
                               ├─► Stream HLS chunks (/stream/hls/{hash}/master.m3u8)
                               └─► Blade/Alpine Dynamic Watermark Canvas:
                                   (Student Name, Email, IP, Timestamp, Dynamic Velocity)
```

### 4.1 Single Device Session Protocol (`DeviceSessionMiddleware`)
- Every authenticated session generates a unique device fingerprint hash (`sha256(user_id + ip + user_agent + hardware_entropy)`).
- When a user signs in, any previously active session record in `device_sessions` is marked `revoked_at = NOW()` with `revocation_reason = 'NEW_DEVICE_LOGIN'`.
- The middleware inspects every incoming request: if the current session token has been revoked in the database, the Laravel session is flushed immediately, returning an HTTP 401/302 with an explicit flash message: *"Your account was logged in from another device or location."*

### 4.2 Dynamic Video Watermarking
- Screen recording deterrence uses a multi-layered Alpine.js dynamic canvas component that renders semi-transparent vector text containing:
  - Student Full Name in Arabic/English
  - Obfuscated Student Phone Number & Email
  - Student Database ID
  - Remote IP address & UTC timestamp
- The watermark smoothly floats along unpredictable vector paths across the video frame, making automated cropping or algorithmic removal virtually impossible without destroying video readability.

---

## 5. Caching, Queueing & Database Performance Strategy

1. **Query Caching:** Redis caching for public read-heavy pages (Course Catalog, Categories, Landing Page Settings) with automatic tag invalidation upon course update (`cache()->tags(['courses'])->flush()`).
2. **Asynchronous Execution:** All external API communications (Zoom API, payment webhook dispatchers, email notifications, PDF rendering) are executed via Redis queues with exponential backoff retries.
3. **Database Concurrency & Locking:**
   - Critical checkout and enrollment records utilize `SELECT ... FOR UPDATE` (Pessimistic Locking) or optimistic version locks to prevent duplicate enrollment grants or double-spending of coupon codes.
   - Foreign key cascading is defined explicitly: non-destructive `RESTRICT` on financial records (`orders`, `payments`, `enrollments`) and `CASCADE` on transient or child curriculum entities (`sections`, `lesson_resources`).

---

## 6. Audit Logging & System Observability

Every security-sensitive event (role changes, manual payment approvals, device kicks, quiz retakes, grade overrides) produces an immutable record in `security_audit_logs`:
- `actor_id` (User who performed the action)
- `target_type` & `target_id` (Entity affected)
- `action` (e.g. `PAYMENT_MANUALLY_APPROVED`, `STUDENT_SESSION_REVOKED`)
- `old_values` & `new_values` (JSON payload)
- `ip_address` & `user_agent`
