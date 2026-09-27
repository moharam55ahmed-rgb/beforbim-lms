# Beforbim — Database Design & Schema Specification (Final Phase 2 Architecture)
**Engine:** MySQL 8.4 (InnoDB)  
**Default Character Set:** `utf8mb4`  
**Collation:** `utf8mb4_unicode_ci` (Full Unicode and Arabic diacritics support)  
**Timezone:** UTC  

---

## 1. Schema Conventions & Engineering Standards

1. **Primary Keys:** `id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY` on transactional tables; `uuid CHAR(36) UNIQUE` exposed externally in URLs and APIs.
2. **Timestamps:** Standard Laravel `created_at` and `updated_at` (nullable `TIMESTAMP` / `DATETIME(0)`).
3. **Soft Deletes:** `deleted_at TIMESTAMP NULL` implemented on high-value business entities (`users`, `courses`, `enrollments`, `orders`, `course_discussions`).
4. **Foreign Keys:** Explicit naming convention with appropriate cascading: `RESTRICT` on financial, academic, and user identity tables; `CASCADE` on child entities (e.g. sections, lessons, review attachments).
5. **Money & Financial Data:** Stored in `DECIMAL(10, 2)` to eliminate floating-point precision loss.
6. **JSON Columns:** Used strictly for semi-structured metadata (options, audit diffs, software requirements).

---

## 2. Table Schemas by Domain

### 2.1 Identity, Access Control & User Lifecycle

#### `users` (Account Lifecycle Management)
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Internal primary key |
| `uuid` | CHAR(36) | UNIQUE, NOT NULL | Public external identifier |
| `name` | VARCHAR(191) | NOT NULL | User full name |
| `email` | VARCHAR(191) | UNIQUE, NOT NULL | Login email address |
| `email_verified_at` | TIMESTAMP | NULL | Email verification timestamp |
| `phone` | VARCHAR(20) | NULL, INDEX | Mobile phone number |
| `phone_verified_at` | TIMESTAMP | NULL | Phone verification timestamp (OTP) |
| `avatar` | VARCHAR(255) | NULL | S3/Storage avatar image path |
| `password` | VARCHAR(255) | NOT NULL | Bcrypt / Argon2id hash |
| `engineering_title` | VARCHAR(120) | NULL | e.g. "BIM Manager", "Structural Design Engineer" |
| `bio` | TEXT | NULL | Biography in Arabic/English |
| `status` | ENUM | NOT NULL, DEFAULT 'active' | `active`, `pending_verification`, `suspended`, `blocked` |
| `max_allowed_devices`| TINYINT UNSIGNED| NOT NULL, DEFAULT 1 | Single-device limit (default 1 for students) |
| `last_login_at` | TIMESTAMP | NULL | Most recent successful login |
| `created_at`, `updated_at`, `deleted_at` | TIMESTAMP | NULL | Soft delete preserves academic/financial history |

*Lifecycle Rules:*
- **Suspension / Block:** Admin can change status between `active`, `suspended`, and `blocked`.
- **Data Preservation:** Security restrictions never delete user data; enrollments, certificates, and exam attempts remain intact for legal and academic compliance.

#### `roles` & `permissions`
- `roles`: `(id, name, display_name_ar, display_name_en, description)`
- `permissions`: `(id, name, group_name, description_ar, description_en)`
- Pivots: `role_user(user_id, role_id)`, `permission_role(role_id, permission_id)`

#### `device_sessions` (Single Active Device Enforcement)
- `(id, user_id, session_token_hash, ip_address, user_agent, device_type, browser, operating_system, is_active, last_activity_at, revoked_at, revocation_reason)`
- When student logs in from Device 2, Device 1 session is revoked (`revocation_reason = 'CONCURRENT_LOGIN_KICK'`).

---

### 2.2 Courses, Curriculum, Lessons & Media

#### `categories`
- `(id, parent_id, name_ar, name_en, slug, description_ar, icon_svg, is_active, display_order)`

#### `courses`
- `(id, uuid, instructor_id, category_id, title_ar, title_en, slug, short_description_ar, description_ar, level, price, sale_price, currency, thumbnail_url, promo_video_url, software_requirements, prerequisites, learning_outcomes, status, rejection_feedback, submitted_at, approved_at, approved_by_user_id, published_at, deleted_at)`
- Status: `DRAFT`, `SUBMITTED`, `APPROVED`, `REJECTED`, `ARCHIVED`.

#### `course_sections` & `lessons`
- `course_sections`: `(id, course_id, title_ar, title_en, description_ar, order_index)`
- `lessons`: `(id, section_id, title_ar, title_en, lesson_type, duration_seconds, order_index, is_preview_free, is_mandatory)`

#### `lesson_contents` & `lesson_resources`
- `lesson_contents`: Video streaming configuration and document markdown.
- `lesson_resources`: Downloadable BIM assets (`.rvt`, `.dwg`, `.ifc`, `.pdf`, `.dyn`).

---

### 2.3 Course Interaction: Reviews, Announcements & Discussions

#### `course_reviews`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Review ID |
| `course_id` | BIGINT UNSIGNED | FK -> `courses(id)`, CASCADE | Reviewed course |
| `student_id` | BIGINT UNSIGNED | FK -> `users(id)`, CASCADE | Enrolled student author |
| `rating` | TINYINT UNSIGNED | NOT NULL | Rating score (1 to 5 stars) |
| `review_text` | TEXT | NULL | Written student feedback |
| `status` | ENUM | NOT NULL, DEFAULT 'pending' | `pending`, `approved`, `rejected` |
| `admin_feedback` | TEXT | NULL | Optional moderation note |
| `created_at`, `updated_at` | TIMESTAMP | NULL | |

*Business Rules:*
- **Enrolled Only:** A student can only submit a review if they hold an `active` enrollment in that course.
- **Uniqueness Guard:** `UNIQUE (course_id, student_id)` prevents spam or duplicate reviews from the same student.
- **Dynamic Rating:** Course average rating is calculated dynamically or cached from `approved` reviews only.

#### `course_announcements`
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Announcement ID |
| `course_id` | BIGINT UNSIGNED | FK -> `courses(id)`, CASCADE | Target course |
| `instructor_id` | BIGINT UNSIGNED | FK -> `users(id)`, CASCADE | Author instructor |
| `title` | VARCHAR(255) | NOT NULL | Announcement title |
| `content` | TEXT | NOT NULL | Announcement body |
| `published_at` | TIMESTAMP | NULL | Publication date (null = draft) |
| `created_at`, `updated_at` | TIMESTAMP | NULL | |

*Access Rule:* Visible strictly to students enrolled in `course_id`. Triggers email and in-app notifications upon publication.

#### `course_discussions` (Threaded Q&A)
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Discussion ID |
| `course_id` | BIGINT UNSIGNED | FK -> `courses(id)`, CASCADE | Course context |
| `user_id` | BIGINT UNSIGNED | FK -> `users(id)`, CASCADE | Author (Student, Instructor, or Admin) |
| `lesson_id` | BIGINT UNSIGNED | FK -> `lessons(id)`, NULL | Optional specific lesson context |
| `parent_id` | BIGINT UNSIGNED | FK -> `course_discussions(id)`, NULL | Parent question for nested threaded replies |
| `message` | TEXT | NOT NULL | Question or answer text |
| `status` | ENUM | NOT NULL, DEFAULT 'active' | `active`, `hidden`, `pinned` |
| `created_at`, `updated_at`, `deleted_at` | TIMESTAMP | NULL | Soft delete support |

*Hierarchy:* `parent_id` enables full 2-level threaded replies (Student Question ➔ Instructor Answer ➔ Follow-up).

---

### 2.4 Commerce, Orders, Payments & Extendable Access

#### Strict Layer Separation:
$$\text{Orders} \longrightarrow \text{Order Items} \longrightarrow \text{Payments} \longrightarrow \text{Transactions (Ledger)} \longrightarrow \text{Enrollments}$$

#### `orders` & `order_items`
- `orders`: `(id, order_number, user_id, subtotal, discount_amount, tax_amount, total_amount, currency, coupon_code, status)`
- `order_items`: `(id, order_id, purchasable_type, purchasable_id, course_id, title_snapshot, unit_price, total_price)`
  - *Extendability:* `purchasable_type` supports `Course`, and future `Bundle`, `LearningPath`, `Subscription`.

#### `payments` & `transactions`
- `payments`: `(id, uuid, order_id, payment_method, gateway, amount, currency, status, receipt_attachment_url, verified_by_user_id, verified_at)`
- `transactions`: `(id, uuid, payment_id, transaction_type, gateway_reference, amount, fee_amount, net_amount, settled_at)`

#### `enrollments` (Extendable Access Architecture)
| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Enrollment ID |
| `user_id` | BIGINT UNSIGNED | FK -> `users(id)`, RESTRICT | Enrolled student |
| `enrollable_type` | VARCHAR(120) | NOT NULL, DEFAULT 'App\\Modules\\Course\\Models\\Course' | Extendable for `Course`, `Bundle`, `LearningPath`, `Subscription` |
| `enrollable_id` | BIGINT UNSIGNED | NOT NULL, INDEX | ID of enrolled entity |
| `course_id` | BIGINT UNSIGNED | FK -> `courses(id)`, NULL | Direct course FK for fast query gating |
| `order_id` | BIGINT UNSIGNED | FK -> `orders(id)`, NULL | Originating order |
| `source` | ENUM | NOT NULL, DEFAULT 'DIRECT_PURCHASE' | `DIRECT_PURCHASE`, `BUNDLE`, `LEARNING_PATH`, `SUBSCRIPTION`, `CORPORATE_TRAINING`, `ADMIN_GRANT` |
| `status` | ENUM | NOT NULL, DEFAULT 'ACTIVE' | `ACTIVE`, `COMPLETED`, `SUSPENDED`, `EXPIRED`, `CANCELLED` |
| `progress_percentage` | DECIMAL(5,2) | NOT NULL, DEFAULT 0.00 | Cached progress |
| `enrolled_at` | TIMESTAMP | NOT NULL | Grant date |
| `completed_at` | TIMESTAMP | NULL | 100% completion date |
| `expires_at` | TIMESTAMP | NULL | Expiration date (null for lifetime access) |
| `created_at`, `updated_at`, `deleted_at` | TIMESTAMP | NULL | |

*Access Rule:* Zero cross-course contamination. Access is granted strictly if active non-expired enrollment exists for the target course.

---

### 2.5 Assessments, Exam Security & Certification

#### `assessments` & `assessment_questions`
- `assessments`: `(id, course_id, lesson_id, title_ar, title_en, type, time_limit_minutes, passing_score_percentage, max_attempts, shuffle_questions, shuffle_options, is_proctored_mode, monitor_tab_switch, monitor_fullscreen_exit, max_violations_allowed, requires_manual_audit)`
- `assessment_questions`: `(id, assessment_id, question_text_ar, question_type, points, options, explanation_ar, order_index)`
- `assessment_attempts`: `(id, assessment_id, user_id, attempt_number, total_points_earned, score_percentage, passed, status, anti_cheat_violations_count, audit_notes, audited_by_user_id)`
- `exam_security_events`: `(id, attempt_id, event_type, severity, details, ip_address, occurred_at)`

#### `certificates`
- `(id, uuid, certificate_number, user_id, course_id, enrollment_id, student_name_snapshot, course_title_snapshot_ar, grade_percentage, issued_at, pdf_storage_path, qr_verification_url, is_revoked)`

---

### 2.6 System Audit Log (`audit_logs`)

| Column | Type | Constraints | Description |
| :--- | :--- | :--- | :--- |
| `id` | BIGINT UNSIGNED | PK, AUTO_INCREMENT | Audit entry ID |
| `actor_id` | BIGINT UNSIGNED | FK -> `users(id)`, NULL, INDEX | User performing action (null for system automated jobs) |
| `actor_role` | VARCHAR(50) | NULL | Role snapshot at action moment |
| `module` | VARCHAR(50) | NOT NULL, INDEX | e.g. `Auth`, `Course`, `Payment`, `Enrollment`, `User`, `Security` |
| `action` | VARCHAR(100) | NOT NULL, INDEX | Traceable business action |
| `target_type` | VARCHAR(120) | NULL | Model class name |
| `target_id` | BIGINT UNSIGNED | NULL, INDEX | Entity ID |
| `old_values` | JSON | NULL | Pre-modification state |
| `new_values` | JSON | NULL | Post-modification state |
| `ip_address` | VARCHAR(45) | NOT NULL | IP address of actor |
| `user_agent` | VARCHAR(500) | NULL | Browser/client signature |
| `reason` | TEXT | NULL | Administrative justification |
| `created_at` | TIMESTAMP | NOT NULL, INDEX | Action timestamp |

*Tracked Critical Actions:*
- `USER_LOGIN`, `USER_LOGOUT`
- `DEVICE_SESSION_REVOKED`, `DEVICE_SESSION_KICKED`
- `COURSE_CREATED`, `COURSE_SUBMITTED`, `COURSE_APPROVED`, `COURSE_REJECTED`
- `ENROLLMENT_GRANTED`, `ENROLLMENT_SUSPENDED`, `ENROLLMENT_REVOKED`
- `PAYMENT_RECEIVED`, `PAYMENT_MANUAL_APPROVED`, `PAYMENT_MANUAL_REJECTED`
- `TRANSACTION_REFUNDED`, `TRANSACTION_CHARGEBACK`
- `ACCOUNT_SUSPENDED`, `ACCOUNT_UNBLOCKED`, `ACCOUNT_STATUS_CHANGED`
- `ROLE_ASSIGNED`, `ROLE_REMOVED`, `PERMISSION_CHANGED`
- `SECURITY_INCIDENT_FLAGGED`, `EXAM_CHEATING_DETECTED`

---

### 2.7 Video Watermark Security Protocol (Privacy-Compliant & Realistic)

1. **Signed URLs:** Time-limited cryptographic URLs (60s validity) for `.m3u8` playlists and `.ts` video chunks.
2. **Dynamic Overlay Only:** Rendered strictly on client canvas at runtime; **never permanently encoded or baked into video storage files**.
3. **Allowed Watermark Data:**
   - Student Full Name (`student_name`)
   - Dynamic Timestamp (`timestamp`)
   - Session Identifier (`session_token_hash` / session ID)
4. **Privacy Protection:** **Zero permanent exposure** of student IP address, mobile phone number, or email address on the watermark canvas.
5. **Realistic Limitations:** System mitigates and traces casual screen-capture tools. It does not make impossible claims of blocking physical external cameras recording computer monitors.
