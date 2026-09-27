# Beforbim — Product Requirements Document (PRD)
**Version:** 1.0.0  
**Status:** Approved Architecture Draft  
**Platform Classification:** Premium Engineering Learning Management System (LMS)  
**Primary Language/Locale:** Arabic (RTL First) with Bilingual English (LTR) Support  

---

## 1. Executive Summary & Vision

**Beforbim** is an enterprise-grade, high-protection Learning Management System specifically engineered for the AEC (Architecture, Engineering, and Construction) and BIM (Building Information Modeling) education sector in the MENA region. 

Traditional generic LMS platforms fail engineering education due to:
1. Piracy and unauthorized credential sharing of high-value courses.
2. Lack of support for complex project-based engineering curricula (Revit, Navisworks, Civil 3D, Dynamo, Tekla files, BIM workflows).
3. Poor Arabic RTL UX and clunky checkout experiences.
4. Vulnerability during high-stakes technical assessments and certification.

Beforbim resolves these challenges through a strict security-first architecture: single active device enforcement, watermarked dynamic video delivery, multi-step admin approval workflows, rigorous payment verification, and dedicated proctoring logic for quizzes and exams.

---

## 2. Core User Personas & Permissions Matrix

### 2.1 Personas

| Persona | Role Description | Key Motivations & Objectives |
| :--- | :--- | :--- |
| **Super Admin** | Platform Owner / Technical Lead | Global system governance, financial reconciliation, security audits, platform health, staff management, CMS configuration. |
| **Admin** | Operations & Academic Manager | Reviewing course submissions, verifying manual/offline wire payments, issuing warnings, handling student support tickets, approving certificate templates. |
| **Instructor** | Engineering & BIM Specialist | Authoring courses, managing curriculum sections, uploading lecture materials (videos, RVT/IFC assets), grading assignments, hosting live sessions, reviewing student progress in own courses. |
| **Student** | Engineering Student / Professional | Browsing catalog, purchasing single courses, viewing DRM/watermarked content, taking timed exams, submitting assignments, tracking progress, earning verifiable certificates. |

### 2.2 Permissions Matrix

| Functional Capability | Super Admin | Admin | Instructor | Student |
| :--- | :---: | :---: | :---: | :---: |
| Full System Configuration & DB Backups | ✅ | ❌ | ❌ | ❌ |
| Create / Edit Any User & Assign Roles | ✅ | ✅ (Non-Super) | ❌ | ❌ |
| Approve / Reject Course Submissions | ✅ | ✅ | ❌ | ❌ |
| Create / Edit Own Courses & Curriculum | ✅ | ✅ | ✅ (Own Only) | ❌ |
| View Other Instructors' Analytics/Revenue | ✅ | ✅ | ❌ | ❌ |
| Verify Manual / Gateway Payments | ✅ | ✅ | ❌ | ❌ |
| Enforce Device Kill & Account Freeze | ✅ | ✅ | ❌ | ❌ |
| Host & Schedule Live BIM Classes | ✅ | ✅ | ✅ (Assigned) | ❌ (Attendee) |
| Grade Assignments & Tasks | ✅ | ✅ | ✅ (Own Courses) | ❌ |
| Attempt Exams & Submit Tasks | ❌ | ❌ | ❌ | ✅ (Enrolled) |
| Access Lesson Player & Streaming Assets | ✅ (Preview) | ✅ (Preview) | ✅ (Own Course) | ✅ (Enrolled Only) |
| Download Course RVT/DWG Resource Files | ✅ | ✅ | ✅ | ✅ (Enrolled Only) |
| Verify Authenticity of Public Certificate | ✅ | ✅ | ✅ | ✅ (Public View) |

---

## 3. High-Priority Business Rules & Constraints

### 3.1 Strict Enrollment & Content Gatekeeping
- **Zero Cross-Course Contamination:** Purchasing Course A **never** unlocks Course B or bundle items unless explicitly bought as an approved bundle order.
- **Payment Verification Gate:** Enrollment is **never** granted on intent. For online gateways (Stripe, Paymob, Tabby, Tamara), the webhook must receive a verified signed charge status before the transaction commits and an enrollment record is created. For manual bank transfers or Fawry/InstaPay, the enrollment remains in `PENDING_APPROVAL` status until an Admin reviews payment proof.
- **Enrolled Scope Policy:** Laravel Authorization Policies (`CoursePolicy@view`, `LessonPolicy@access`) must check an active, non-expired, non-suspended enrollment record in `enrollments` table. If false, throw `403 Forbidden`.

### 3.2 Single Active Device Session Enforcement (Anti-Piracy)
- Each student account is restricted to **one active concurrent session**.
- Upon a new login from a different browser or device:
  - If a session is active, the system terminates the previous session, marks the previous device token as invalidated in the `device_sessions` table, and logs the IP, User Agent, and approximate location.
  - If anomalous concurrent activity is detected (e.g. rapid IP hopping between cities within 15 minutes), the account enters `FLAGGED` status and creates an automated `SecurityWarning`.
  - Three unacknowledged security warnings trigger an automatic temporary lock requiring Admin review.

### 3.3 Dynamic Watermarking & Content Protection
- Video lessons must never expose direct S3/storage URLs or raw MP4 paths to the client.
- Playback requires time-limited, signed URLs (via HLS/m3u8 or secure tokenized streaming).
- The video player must dynamically overlay an animated, semi-transparent watermark displaying the student's name, email, IP address, and unique student ID bouncing across the frame at pseudo-random intervals to deter screen recording.

### 3.4 Multi-Tier Course Quality & Publishing Workflow
- An Instructor cannot publish a course directly to the public catalog.
- Course states: `DRAFT` ➔ `SUBMITTED_FOR_REVIEW` ➔ `UNDER_AUDIT` ➔ `APPROVED / PUBLISHED` (or `REJECTED_WITH_FEEDBACK`).
- An Instructor can only edit draft lessons or request a revision after a course is approved.

### 3.5 Exam Integrity & Anti-Cheating Protocol
- Timed exams run a countdown synchronized with the server via signed heartbeat timestamps (client clock tampering is rejected).
- Fullscreen change, tab switching, and window blur events are recorded. Exceeding the maximum allowed violations (configurable, default: 3) will auto-submit the exam with a penalty flag.
- Questions and options can be randomized per student attempt.

---

## 4. Detailed Functional Requirements (Module by Module)

### M01: Authentication & Authorization
- Secure multi-guard authentication: email/password with rate-limited brute-force protection.
- Remember-me tokens with rotating device-bound hashes.
- Two-factor authentication (TOTP) readiness for Admin and Instructor accounts.
- Password reset via signed, time-limited cryptographic tokens.

### M02 & M03: Users Management & RBAC
- Role-based access control with granular permission strings (e.g., `courses.create`, `courses.publish`, `payments.verify`, `users.ban`).
- Soft-delete support for user accounts to maintain historical financial and academic integrity.
- Profile management with engineering specialization tags (BIM Manager, Structural Engineer, MEP Modeler, etc.).

### M04, M05, M06, M07, M08: Course Catalog & Curriculum Architecture
- Hierarchical categories (e.g., *Civil Engineering > BIM Modeling > Revit Architecture*).
- Courses have levels (Beginner, Intermediate, Advanced), prerequisite courses, learning outcomes, software requirements (e.g., *Autodesk Revit 2024, Dynamo 3.0*).
- Course Curricula consist of `Sections` (Modules) containing sequential `Lessons`.
- Lesson types: `Video`, `Document / Reading`, `Live Session`, `Quiz`, `Project Assignment`.
- Material attachments: RVT project files, IFC files, Family files (RFA), PDF guidelines, CAD DWG drawings.

### M09, M10, M11, M12: Commerce, Checkout & Enrollments
- Persistent user cart (database-backed for authenticated users, session-backed for guests).
- Multi-currency support (SAR, AED, EGP, USD) with base currency standardization.
- Coupon and discount engine (percentage, fixed amount, per-course, minimum basket size, expiration date, max usage limits).
- Orders table with immutable line items snapshots (recording exact price, tax, and discount at transaction moment).
- Idempotent payment webhooks handling gateway callbacks with signature verification and replay attack prevention.

### M13: Live Interactive Classes
- Integration with Zoom Meeting API and Google Meet.
- Scheduled sessions linked to course curriculum or stand-alone cohort webinars.
- Automated attendance tracking based on join timestamps.

### M14 & M15: Assignments & Engineering Tasks
- Project-based submissions supporting large engineering files (ZIP, RVT, DWG, PDF).
- Rubric-based grading system for Instructors with feedback attachments and revision requests.
- Deadlines with automated late submission penalties.

### M16 & M17: Quizzes, Exams & Exam Security
- Multiple question types: Multiple Choice (single answer), Multiple Choice (multiple answers), True/False, Short Answer, File Calculation Upload.
- Time limits with auto-submit on expiry.
- Anti-cheating telemetry logging: tab switches, blur counts, copy/paste attempts.

### M18 & M19: Device Sessions, Warnings & Restrictions
- Real-time device session table with UUID, User-Agent parser (Device family, OS, Browser), IP geolocation, last activity timestamp.
- Remote logout mechanism: User or Admin can invalidate any active session.
- Automatic account restriction on repeated suspicious concurrent session flags.

### M20 & M21: Progress Tracking & Automated Certification
- Lesson progress tracking (percentage of video watched, mandatory quiz pass before next lesson unlock).
- Sequential vs. open progression modes configured per course.
- Automated certificate issuance upon 100% curriculum completion and passing all mandatory assessments.
- Unique QR-code and cryptographic hash on each certificate for public verification on `beforbim.test/verify/{uuid}`.

### M22, M23, M24: Notifications, Analytics & CMS
- Multi-channel notification pipeline (Database in-app notifications, transactional emails via Mailpit/SMTP, SMS/WhatsApp ready).
- Executive analytics dashboard for Admins: Revenue, active learners, course completion rates, drop-off hotspots.
- CMS settings engine: site identity, SEO metadata, landing page banners, terms of service, payment keys.

---

## 5. Non-Functional & Operational Requirements

1. **Performance:** Sub-100ms response time for cached course catalog endpoints; sub-200ms response time for lesson player navigation.
2. **Database Integrity:** Strict foreign key constraints with InnoDB on MySQL 8.4; atomic database transactions on all checkout and enrollment state mutations.
3. **Security:** OWASP Top 10 compliance, CSRF protection on all mutating routes, strict input sanitization, CSP headers, rate-limiting on sensitive auth and checkout endpoints.
4. **Localization (Arabic RTL First):** Native right-to-left layout as primary viewport; clean bidirectional typography with Latin terms preserved for technical acronyms (BIM, IFC, LOD 300, MEP, Dynamo).
