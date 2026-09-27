# Beforbim — Frontend Design System & UI/UX Architecture
**Target Audience:** Civil, Architectural, Structural & Mechanical Engineers in the MENA Region  
**Design Direction:** High-End Technical / Engineering EdTech  
**Primary Direction:** Arabic RTL First (`dir="rtl"`) with Bilingual English support  
**Stack:** Blade Components + Tailwind CSS v4 + Alpine.js + Vite  

---

## 1. Visual Theme & Brand Identity

Beforbim's visual identity reflects structural precision, architectural blueprints, computational intelligence, and modern engineering aesthetics.

### 1.1 Curated Color Palette (Tailwind Tokens)

| Token Name | Hex Code | HSL | Semantic Role |
| :--- | :--- | :--- | :--- |
| `primary-navy` | `#0B132B` | `223, 59%, 11%` | Deep background, header navigation, dominant structural tone. |
| `primary-dark` | `#1C2541` | `225, 40%, 18%` | Card backgrounds in dark theme, secondary surfaces. |
| `bim-cyan` | `#00D2D3` | `181, 100%, 41%` | Primary accent, dynamic highlights, progress bars, active states. |
| `cyan-glow` | `#48DBFB` | `190, 95%, 63%` | Hover glows, interactive focus rings, active BIM lesson indicators. |
| `blueprint-blue`| `#2563EB` | `217, 91%, 60%` | Primary action buttons, verified badges, links. |
| `accent-emerald`| `#10B981` | `152, 84%, 39%` | Success, completed lessons, approved orders, verified certificates. |
| `accent-amber` | `#F59E0B` | `38, 92%, 50%` | Warnings, pending verification, exam countdown timer alert. |
| `accent-rose` | `#EF4444` | `0, 84%, 60%` | Danger, payment failed, device session revoked, cheating alert. |
| `surface-light`| `#F8FAFC` | `210, 40%, 98%` | Light mode page background (Slate 50). |
| `surface-card` | `#FFFFFF` | `0, 0%, 100%` | Light mode elevated card surface. |
| `text-primary` | `#0F172A` | `222, 47%, 11%` | High contrast primary typography (Slate 900). |
| `text-muted` | `#64748B` | `215, 16%, 47%` | Secondary and meta typography (Slate 500). |

---

## 2. Typography Architecture (Arabic RTL First)

Engineering platforms require clear, geometric, legible Arabic type that scales effortlessly alongside Latin technical jargon (e.g. *LOD 350, Dynamo, Navisworks Manage, IFC 4.3*).

### 2.1 Font Stack
- **Primary Arabic Font:** `IBM Plex Sans Arabic` or `Tajawal` (Google Fonts)
  - Geometric sans-serif with high legibility at technical tables and code snippets.
- **English / Technical Monospace:** `JetBrains Mono` or `Fira Code`
  - Used for parameter equations, Dynamo Python nodes, code blocks, and transaction hashes.

### 2.2 Typographic Hierarchy
```css
/* Display Titles */
.font-display { font-family: 'Tajawal', 'IBM Plex Sans Arabic', sans-serif; font-weight: 800; }

/* Body & Interface */
.font-sans    { font-family: 'IBM Plex Sans Arabic', sans-serif; }

/* Code & Data Monospace */
.font-mono    { font-family: 'JetBrains Mono', monospace; direction: ltr; text-align: left; }
```

---

## 3. Core Component Library Specifications

### 3.1 Reusable Blade Components

1. `<x-button variant="primary|secondary|danger|outline" size="sm|md|lg">`
   - Smooth micro-interactions, loading spinners with SVG, RTL icon mirroring.
2. `<x-card class="..." elevated="true">`
   - Clean border (`border-slate-200/80`), subtle drop shadow (`shadow-sm hover:shadow-md transition-all duration-200`).
3. `<x-badge status="active|pending|rejected|draft">`
   - Rounded pill badge with localized Arabic status text and dot indicator.
4. `<x-video-player>`
   - Custom styled HTML5/HLS player wrapper with built-in dynamic floating canvas watermark.
5. `<x-curriculum-accordion>`
   - Expandable section view with lesson duration icons, completion checkmarks, and locked padlocks.
6. `<x-device-session-card>`
   - Displays device icon (desktop/mobile), operating system, IP, last active relative timestamp, and a "Kick Device" button.

---

## 4. Layout Strategy & Viewport Archetypes

### 4.1 Layout Shell 1: Public / Marketing & Course Catalog
- **Header:** Sticky glassmorphism nav (`backdrop-blur-md bg-white/90`), search bar for BIM courses, cart counter badge, localized language switcher, auth CTA.
- **Hero:** Technical blueprint grid background with high-impact headline:  
  *«المنصة الهندسية الأولى لاحتراف نمذجة معلومات البناء (BIM) وإدارة المشروعات»*
- **Footer:** Accreditation badges, payment gateway logos (Mada, Visa, Tabby, Tamara), terms, and verification lookup link.

### 4.2 Layout Shell 2: Student Learning Portal (`/learn/{course-slug}`)
- Focused, distraction-free environment:
  - **Left (in RTL):** Full-height collapsible curriculum navigation drawer with progress percentage tracker.
  - **Right (in RTL):** Main video player stage with dynamic watermark canvas, lesson resources download tab, Q&A discussion tab, and notes drawer.

### 4.3 Layout Shell 3: Instructor Studio (`/instructor/...`)
- Focus on curriculum authoring, student performance metrics, and assignment submissions.
- Fast drag-and-drop section/lesson organizer using Alpine.js and HTML5 drag-and-drop.
- Submission grading queue with inline PDF viewer and download buttons for Revit `.rvt` and CAD `.dwg` models.

### 4.4 Layout Shell 4: Admin Control Center (`/admin/...`)
- Clean, data-dense, dark sidebar layout:
  - Financial telemetry (daily revenue, pending bank receipts requiring verification).
  - Security command center (real-time active device sessions, suspicious login flags).
  - Course submission audit queue with side-by-side video and syllabus preview before approval.

---

## 5. RTL & Bidirectional Engineering Rules

1. **Logical CSS Properties:**
   - Always favor `ms-` (margin-inline-start) and `me-` (margin-inline-end) over `ml-` and `mr-`.
   - Always favor `ps-` (padding-inline-start) and `pe-` (padding-inline-end) over `pl-` and `pr-`.
   - Always favor `start-0` / `end-0` over `left-0` / `right-0`.
2. **Technical LTR Isolations:**
   - Numerical values, telephone numbers, and technical abbreviations must be wrapped with `<bdi dir="ltr">` or `.dir-ltr` to prevent bidirectional text scrambling:
   ```html
   <p>الدورة تتطلب برنامج <span dir="ltr" class="font-mono text-cyan-600">Autodesk Revit 2024</span> وما بعده.</p>
   ```
3. **Mirrored Icons:**
   - Directional icons (arrows, chevrons, next/prev lesson buttons) must flip in RTL using Tailwind's `rtl:rotate-180`.
