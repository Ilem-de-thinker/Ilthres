# Support Widget + Business Report System — TODO

## Overview

Two floating widgets + admin management panel. One shared `support_tickets` table handles both:
- **Support** — business owners contact admin from the dashboard
- **Report** — customers report/complain about a business from public pages (goes to admin, NOT the business)

## Database

- [ ] Create `support_tickets` migration
  - Columns: id, type (enum: support/report), user_id (nullable FK), business_id (nullable FK), category (nullable), subject (nullable), description (text), status (enum: open/in_progress/resolved/closed), admin_notes (nullable), timestamps, soft_deletes
- [ ] Run migration

## Model

- [ ] Create `app/Models/SupportTicket.php`
  - SoftDeletes, fillable, constants for type/status, relationships (user, business), scopes

## Notification

- [ ] Create `app/Domains/Notifications/Notifications/SupportTicketReceived.php`
  - Database + mail, sends to all admin users

## Support Domain — Livewire Components

- [ ] Create `app/Domains/Support/Livewire/SupportWidget.php` — dashboard floating widget
  - Tabs: Support (category dropdown + subject + description) + Write Review (inline)
  - Creates SupportTicket with type=support
- [ ] Create `app/Domains/Support/Livewire/BusinessReportWidget.php` — public page floating widget
  - Description textarea only
  - Creates SupportTicket with type=report

## Blade Views

- [ ] Create `resources/views/livewire/support/support-widget.blade.php`
- [ ] Create `resources/views/livewire/support/business-report-widget.blade.php`

## Layout Integration

- [ ] Add `@livewire('support.support-widget')` to `resources/views/layouts/dashboard.blade.php`
- [ ] Add `<livewire:support.business-report-widget>` to public business pages:
  - `resources/views/livewire/business/business-detail.blade.php`
  - `resources/themes/*/service/service-detail.blade.php`
  - `resources/themes/*/product/product-detail.blade.php` (if exists)

## Admin Management

- [ ] Create `app/Domains/Administration/Livewire/SupportTicketManager.php`
  - Filter by type, status; view ticket details; update status; admin notes
- [ ] Create `resources/views/livewire/administration/support-ticket-manager.blade.php`
- [ ] Add admin route in `routes/web.php`
- [ ] Add nav link in `resources/views/layouts/admin.blade.php`

## Events

- [ ] Create `app/Domains/Support/Events/TicketCreated.php`
- [ ] Create `app/Domains/Support/Listeners/SendTicketNotification.php`
- [ ] Register in `app/Providers/EventServiceProvider.php`

## Verification

- [ ] Run `php artisan migrate`
- [ ] Run `pint` on all new/edited files
- [ ] Test dashboard widget renders and submits
- [ ] Test public report widget renders and submits
- [ ] Test admin page shows tickets
