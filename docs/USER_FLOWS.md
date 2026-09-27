# Beforbim — User Flows & State Transition Specifications
**Platform:** Beforbim Engineering LMS  
**Audience:** Technical Engineers, Students, Instructors, Operations Staff  

---

## 1. Flow 1: Course Enrollment & Payment Verification (Zero Leaks)

This flow enforces two fundamental business rules:
1. **One course purchase unlocks ONLY that specific course.**
2. **Access is strictly gated behind verified payment.**

```mermaid
sequenceDiagram
    autonumber
    actor Student
    participant Cart as M10: Cart
    participant Order as M11: Order
    participant Payment as M12: Payment
    participant Gateway as External Payment Gateway / Bank
    participant Admin as Operations Admin
    participant Enrollment as M09: Enrollment

    Student->>Cart: Add Course A to Cart
    Cart-->>Student: Display Cart Total (Course A only)
    Student->>Order: Proceed to Checkout
    Order->>Order: Create Order (Status: PENDING)
    Order->>Order: Create OrderItem (Course A, Snapshot Price)
    
    alt Online Payment (Mada, Credit Card, Tabby, Apple Pay)
        Student->>Payment: Select Gateway & Submit
        Payment->>Gateway: Initialize Transaction
        Gateway-->>Student: Complete Payment on Gateway / 3DS
        Gateway->>Payment: Webhook (Status: CHARGE_SUCCEEDED, Verified Signature)
        Payment->>Payment: Record Payment (Status: SUCCESS)
        Payment->>Order: Transition Order (Status: COMPLETED)
        Payment--)Enrollment: Dispatch PaymentVerifiedEvent
        Enrollment->>Enrollment: Insert Enrollment (User, Course A, Status: ACTIVE)
        Enrollment-->>Student: Course A unlocked in Student Dashboard
    else Offline Bank Wire / InstaPay Transfer
        Student->>Payment: Upload Bank Transfer Receipt Image
        Payment->>Payment: Record Payment (Status: REQUIRES_ADMIN_VERIFICATION)
        Payment-->>Student: Order Placed (Status: PENDING_VERIFICATION)
        Admin->>Payment: Inspect Bank Statement & Receipt Attachment
        alt Admin Approves
            Admin->>Payment: Click "Approve Payment"
            Payment->>Payment: Set Status: SUCCESS, verified_by_user_id = Admin
            Payment->>Order: Transition Order (Status: COMPLETED)
            Payment--)Enrollment: Dispatch PaymentVerifiedEvent
            Enrollment->>Enrollment: Insert Enrollment (User, Course A, Status: ACTIVE)
            Enrollment-->>Student: Email notification & Instant Course A Access
        else Admin Rejects
            Admin->>Payment: Click "Reject Payment" (Reason: "Invalid Reference")
            Payment->>Payment: Set Status: FAILED
            Payment->>Order: Set Status: CANCELLED
            Payment-->>Student: Email Alert with Rejection Reason
        end
    end
```

---

## 2. Flow 2: Single Active Device Session Enforcement (Anti-Piracy)

Ensures that student credentials cannot be shared with colleagues or public groups.

```mermaid
sequenceDiagram
    autonumber
    actor StudentDevice1 as Student (Device 1: Office PC)
    actor StudentDevice2 as Unauthorized User (Device 2: Home Laptop)
    participant Auth as M01: Auth
    participant DeviceSession as M18: DeviceSession
    participant DB as MySQL: device_sessions

    Note over StudentDevice1,DB: StudentDevice1 is logged in and actively watching a Revit course.
    
    StudentDevice2->>Auth: Submit Login (Email + Password)
    Auth->>Auth: Validate Credentials OK
    Auth->>DeviceSession: RegisterNewSession(User ID, Device 2 Fingerprint, IP)
    
    DeviceSession->>DB: Query existing active sessions for User ID
    DeviceSession->>DB: Update active sessions SET is_active = FALSE, revoked_at = NOW(), reason = 'CONCURRENT_LOGIN_KICK'
    DeviceSession->>DB: Insert new active session for Device 2
    Auth-->>StudentDevice2: Login Successful, Set Session Cookie
    
    Note over StudentDevice1,DB: StudentDevice1 makes next request or video heartbeat
    
    StudentDevice1->>DeviceSession: Next HTTP Request / Heartbeat
    DeviceSession->>DB: Check session token active status
    DB-->>DeviceSession: Record is revoked (reason: CONCURRENT_LOGIN_KICK)
    DeviceSession->>StudentDevice1: Invalidate Laravel Session, Destroy Cookie
    DeviceSession-->>StudentDevice1: Redirect to /login with Flash Alert:<br/>"تم تسجيل الدخول من جهاز آخر. تم إنهاء جلستك الحالية لحماية حسابك."
```

---

## 3. Flow 3: Course Creation & Admin Quality Approval Workflow

Ensures instructors can only manage their own courses and cannot publish directly to the public store without academic audit.

```mermaid
stateDiagram-v2
    [*] --> DRAFT: Instructor Creates New Course
    
    state DRAFT {
        [*] --> EditingCurriculum: Add Sections & Lessons
        EditingCurriculum --> UploadingMedia: Upload RVT/IFC Assets & Videos
        UploadingMedia --> SetPricing: Define Price & Software Req
    }
    
    DRAFT --> SUBMITTED: Instructor Submits for Review
    
    state SUBMITTED {
        [*] --> UnderReviewByAdmin: Admin / Academic Auditor Inspects
    }
    
    UnderReviewByAdmin --> REJECTED: Content/Quality Deficiencies Found
    REJECTED --> DRAFT: Instructor Receives Detailed Feedback & Edits
    
    UnderReviewByAdmin --> APPROVED: Course Meets Academic & BIM Standards
    APPROVED --> PUBLISHED: Course Listed in Public Catalog
    
    PUBLISHED --> [*]
```

### Strict Boundaries:
- `InstructorCourseController`: Queries always scoped by `where('instructor_id', auth()->id())`.
- Route policies strictly prevent Instructor X from viewing, editing, or querying courses authored by Instructor Y.

---

## 4. Flow 4: Dynamic Watermarked Video Delivery & Content Security

Protects high-value proprietary engineering lectures from screen capturing.

```mermaid
sequenceDiagram
    autonumber
    actor Student
    participant Policy as LessonPolicy
    participant MediaService as M08: SecureMediaStreaming
    participant Player as Blade/Alpine Video Player Component
    participant S3 as Storage Bucket (Private)

    Student->>Player: Click Lesson "Revit Structural Modeling - Part 2"
    Player->>Policy: Request Playback Authorization
    Policy->>Policy: Verify active enrollment in parent Course
    
    alt Enrolled Active
        Policy->>MediaService: GenerateSignedStreamingToken(LessonId, StudentId)
        MediaService-->>Player: Return Signed Short-Lived URL (Expires in 60s) + Watermark Config
        Player->>S3: Fetch HLS Master Manifest with Token
        S3-->>Player: Stream Video Chunks
        Player->>Player: Render Dynamic Canvas Overlay:<br/>- Student Name: "م. أحمد الشمري"<br/>- Student ID: "BFB-88392"<br/>- Email: "a***@gmail.com"<br/>- IP: "185.12.98.24"<br/>(Animated Floating Velocity Vector)
    else Not Enrolled
        Policy-->>Student: 403 Forbidden ("يجب الاشتراك في الدورة أولاً للوصول إلى هذا المحتوى")
    end
```

---

## 5. Flow 5: High-Stakes Exam Proctoring & Integrity Flow

Protects engineering certification exams against cheating, browser switching, and unauthorized external aid.

```mermaid
sequenceDiagram
    autonumber
    actor Student
    participant ExamView as Blade/Alpine Exam Interface
    participant Telemetry as M17: ExamSecurity
    participant Engine as M16: AssessmentEngine

    Student->>Engine: Start Certification Exam
    Engine->>Engine: Verify Eligibility & Remaining Attempts
    Engine->>Engine: Generate Shuffled Question Sequence
    Engine-->>ExamView: Deliver Questions (Server Countdown Initialized)
    ExamView->>ExamView: Enter Proctored Fullscreen Mode
    
    loop During Exam
        ExamView->>Telemetry: Send Signed Heartbeat (every 15s)
        
        alt Student Switches Tab / Window Blurs
            ExamView->>Telemetry: POST /exam-security/log-event (Type: TAB_BLUR)
            Telemetry->>Telemetry: Increment Violation Count
            Telemetry-->>ExamView: Warning Modal: "تحذير: لا يُسمح بمغادرة نافذة الاختبار!"
            
            opt Violations >= 3
                Telemetry->>Engine: Force Auto-Submit Exam (Flag: CHEATING_DETECTED)
                Engine-->>ExamView: Exam Terminated Immediately
            end
        end
    end
    
    Student->>Engine: Submit Completed Exam
    Engine->>Engine: Auto-Grade Objective Questions
    Engine-->>Student: Display Preliminary Score & Breakdown
```
