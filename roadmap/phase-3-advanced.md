# Phase 3 — Advanced

> **Goal:** Turn business profiles into full operational tools — appointments, team management, and custom domains.  
> **Prerequisite:** Phase 2 complete  
> **Livewire Components:** ~15  
> **Database Tables:** ~8

---

## Features

### 1. Appointments & Booking (Service Businesses only)

Customers book appointments with service businesses. Calendar integration. This feature is only available for `service_business` type.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Appointment\BookingWidget` | `php artisan make:livewire Appointment\\BookingWidget` |
| `Appointment\AppointmentCalendar` | `php artisan make:livewire Appointment\\AppointmentCalendar` |
| `Appointment\AppointmentList` | `php artisan make:livewire Appointment\\AppointmentList` |
| `Appointment\CreateAppointment` | `php artisan make:livewire Appointment\\CreateAppointment` |
| `Appointment\AvailabilityManager` | `php artisan make:livewire Appointment\\AvailabilityManager` |

**Models:** `Appointment`, `AvailabilitySlot`, `BookingSchedule` (with `--migration`)

**Actions:**
- `Appointment\CreateAppointment`
- `Appointment\RescheduleAppointment`
- `Appointment\CancelAppointment`
- `Appointment\ConfirmAppointment`
- `Appointment\UpdateAvailability`

**Events:** `AppointmentBooked`, `AppointmentRescheduled`, `AppointmentCancelled`, `AppointmentConfirmed`, `AvailabilityUpdated`

**Policies:** `AppointmentPolicy` — customer books, merchant manages

---

### 2. Lead Management

Capture, track, and manage inbound customer leads.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Marketing\LeadInbox` | `php artisan make:livewire Marketing\\LeadInbox` |
| `Marketing\LeadDetail` | `php artisan make:livewire Marketing\\LeadDetail` |
| `Marketing\LeadForm` | `php artisan make:livewire Marketing\\LeadForm` |

**Models:** `Lead`, `LeadNote` (with `--migration`)

**Actions:** `Marketing\CaptureLead`, `Marketing\UpdateLeadStatus`, `Marketing\AddLeadNote`, `Marketing\AssignLead`

**Events:** `LeadCaptured`, `LeadConverted`, `LeadStatusChanged`

**Policies:** `LeadPolicy`

---

### 3. Teams & Staff

Multiple staff members can manage a business with role-based permissions.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Business\TeamList` | `php artisan make:livewire Business\\TeamList` |
| `Business\InviteStaff` | `php artisan make:livewire Business\\InviteStaff` |
| `Business\ManagePermissions` | `php artisan make:livewire Business\\ManagePermissions` |

**Models:** `TeamMember`, `Invitation`, `Permission` (with `--migration`)

**Actions:**
- `Business\InviteTeamMember`
- `Business\AcceptInvitation`
- `Business\RevokeInvitation`
- `Business\RemoveTeamMember`
- `Business\UpdatePermissions`

**Events:** `StaffInvited`, `StaffInvitationAccepted`, `StaffRemoved`, `PermissionsUpdated`

**Policies:** `TeamMemberPolicy` — only business owner can manage team

---

### 4. Custom Domains

Merchants connect their own domain to their Ilthres business page.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Business\CustomDomain` | `php artisan make:livewire Business\\CustomDomain` |
| `Business\DnsVerification` | `php artisan make:livewire Business\\DnsVerification` |

**Models:** `CustomDomain` (with `--migration`)

**Actions:**
- `Business\RegisterDomain`
- `Business\VerifyDomain`
- `Business\RemoveDomain`

**Events:** `DomainRegistered`, `DomainVerified`, `DomainRemoved`

**Jobs:** `VerifyDnsRecord` — periodic check if DNS is configured correctly
**Jobs:** `RenewSslCertificate` — auto-renew Let's Encrypt certificates

---

### 5. Invoices

Generate and send invoices to customers. Payment tracking.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Payments\InvoiceList` | `php artisan make:livewire Payments\\InvoiceList` |
| `Payments\CreateInvoice` | `php artisan make:livewire Payments\\CreateInvoice` |
| `Payments\InvoiceDetail` | `php artisan make:livewire Payments\\InvoiceDetail` |

**Models:** `Invoice`, `InvoiceItem`, `Payment` (with `--migration`)

**Actions:** `Payments\CreateInvoice`, `Payments\SendInvoice`, `Payments\RecordPayment`, `Payments\VoidInvoice`

**Events:** `InvoiceCreated`, `InvoiceSent`, `InvoicePaid`, `InvoiceOverdue`

---

### 6. Advanced Reports

Exportable analytics reports. PDF/CSV download.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `Analytics\ReportBuilder` | `php artisan make:livewire Analytics\\ReportBuilder` |
| `Analytics\ReportList` | `php artisan make:livewire Analytics\\ReportList` |
| `Analytics\ReportViewer` | `php artisan make:livewire Analytics\\ReportViewer` |

**Models:** `Report` (with `--migration`)

**Actions:** `Analytics\GenerateReport`, `Analytics\ExportReport`, `Analytics\ScheduleReport`

**Jobs:** `GenerateReportExport` — generates CSV/PDF asynchronously
**Jobs:** `SendScheduledReport` — email periodic reports to merchant

---

## Database Tables (Phase 3)

| Table | Purpose | Business Type |
|-------|---------|---------------|
| `appointments` | Customer bookings | `service_business` only |
| `availability_slots` | Merchant available times | `service_business` only |
| `booking_schedules` | Weekly schedule templates | `service_business` only |
| `leads` | Inbound customer inquiries | Both |
| `lead_notes` | Internal notes on leads | Both |
| `team_members` | Staff linked to business | Both |
| `invitations` | Pending staff invitations | Both |
| `permissions` | Granular role permissions | Both |
| `custom_domains` | Merchant custom domains | Both |
| `invoices` | Customer invoices | Both |
| `invoice_items` | Invoice line items | Both |
| `payments` | Payment records | Both |
| `reports` | Saved analytics reports | Both |

---

## Queue Jobs (Phase 3)

| Job | Queue | Purpose |
|-----|-------|---------|
| `SendAppointmentReminder` | `notifications` | 24h before appointment |
| `VerifyDnsRecord` | `default` | Check DNS config periodically |
| `RenewSslCertificate` | `default` | Auto-renew SSL |
| `GenerateReportExport` | `media` | Async CSV/PDF generation |
| `SendScheduledReport` | `emails` | Email periodic reports |
| `SendInvoiceReminder` | `emails` | Overdue invoice reminder |
