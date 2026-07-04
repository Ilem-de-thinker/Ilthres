# Ilthres Market
# CTO Architecture & Product Blueprint

Version: 1.0
Architecture: Domain Driven SaaS
Framework: Laravel 12 + Livewire 4
Frontend: Alpine.js + TailwindCSS
Database: MySQL
Cache: Redis
Queue: Redis
Storage: S3 Compatible Storage
Search: Database (MVP) → Meilisearch (Future)

---

# Product Philosophy

Ilthres Market is **not a marketplace.**

It is a **Digital Business Presence Platform**.

Businesses use Ilthres Market to create a professional online presence without building a website.

Customers use Ilthres Market to discover trusted businesses.

The marketplace is simply a discovery layer.

Business comes first.

Marketplace comes second.

---

# Core Principles

- Mobile First
- Performance First
- Security by Default
- Accessibility
- SEO Friendly
- Multi Tenant
- Modular
- Theme Driven
- Event Driven Analytics

---

# High Level Architecture

Platform

├── Landing Website

├── Explore Marketplace

├── Merchant Dashboard

├── Public Business Pages

├── Theme Engine

├── Search Engine

├── Analytics Engine

├── Subscription Engine

├── Notification Engine

├── Media Engine

└── Administration Panel

Every module should work independently.

Never tightly couple modules together.

---

# Multi Tenant Strategy

Single Database

Shared Tables

Tenant Isolation via business_id

users

businesses

products

services

analytics_events

media

categories

themes

subscriptions

notifications

settings

Every table belonging to a merchant contains

business_id

Never query merchant resources without tenant scope.

---

# Domain Structure

app/

Domains/

Authentication/

Business/

Merchant/

Product/

Service/

Theme/

Search/

Analytics/

Subscription/

Payments/

Media/

Marketing/

Notifications/

Reviews/

Administration/

Each domain owns

Actions

DTOs

Events

Listeners

Policies

Services

Jobs

Rules

Validation

Livewire Components

Never organize code by Controller.

Organize by Business Domain.

---

# Layered Architecture

Presentation

↓

Livewire Components

↓

Actions

↓

Services

↓

Repositories (if needed)

↓

Models

↓

Database

Business rules live inside Actions and Services.

Never inside Livewire components.

---

# Event Driven Design

Everything important fires events.

BusinessCreated

ProductCreated

StoreViewed

ProductViewed

WhatsAppClicked

PhoneClicked

SubscriptionStarted

SubscriptionExpired

ThemeChanged

ReviewSubmitted

Future modules simply listen for events.

No code duplication.

---

# Module Overview

Authentication

Business

Products

Services

Portfolio

Media

Analytics

Themes

SEO

Marketing

Search

Subscription

Payments

Notifications

Admin

Support

Each module should be deployable independently in future.

---

# Feature Flags

Every major feature should be toggleable.

Example

Appointments

Reviews

Premium Features

AI Assistant

Custom Domains

QR Codes

Booking

This allows controlled rollouts.

---

# Data Ownership

User owns Account

Business owns

Products

Services

Portfolio

Analytics

Media

SEO

Theme

Subscription

Everything belongs to a Business.

Not directly to a User.

Users simply manage Businesses.

Future:

One user

↓

Multiple Businesses

Multiple Staff

Permissions

---

# UI & UX Architecture

Design Language

Minimal

Elegant

Professional

Modern SaaS

High whitespace

Rounded components

Soft shadows

Large typography

Fast interactions

No clutter.

---

# Navigation

## Visitors

### Top Navigation

Logo

Explore

Categories

Pricing

About

Login

Create Business

### Footer

Resources

Privacy

Terms

Support

Socials

---

## Merchant Dashboard

### Desktop

Top Navbar

Secondary Navigation (no permanent sidebar, becomes sticky)

Dashboard

Business

Products / Services

Media

Analytics

Marketing

SEO

Appearance

Subscription

Settings

Notifications

Support

### Mobile

Bottom Navigation

Dashboard

Business

Products

Analytics

Settings

---

## Landing Page

### Sections

Hero

Featured Businesses

Popular Categories

How It Works

Business Types

Marketplace Preview

Testimonials

Pricing

FAQ

CTA

Footer

---

## Explore Page

Search always visible.

### Layout

Search Bar

Filter Sidebar (Desktop)

Filter Drawer (Mobile)

Results Grid

Pagination

### Quick Filters

Verified

Premium

Nearby

Open Now

Online

Trending

### Sort

Newest

Popular

Recently Updated

Most Viewed

---

## Business Profile

Hero Cover

Logo

Business Information

Quick Actions: Call, WhatsApp, Directions, Website

Products or Services

About

Gallery

Portfolio

Reviews (Future)

Related Businesses

Footer

---

## Merchant Dashboard

### Dashboard Cards

Views

Visitors

Clicks

Products

Services

Subscription

Analytics Graph

Recent Activity

### Quick Actions

Add Product

Upload Media

Share Store

Change Theme

Upgrade

---

## Products Page

Search

Filters

Table/Grid Toggle

Bulk Actions

Categories

Pagination

Status Badges

Quick Edit

---

## Services Page

Similar to Products

Different Fields: Duration, Booking (Future), Availability

---

## Media Library

Grid View

Upload

Folders

Search

Drag and Drop

Preview

Delete

Reuse Across Platform

---

## Theme Page

Preview Cards

Live Preview

Activate

Customize: Accent Color, Typography, Rounded Corners, Animations

---

## Analytics Page

Overview Cards: Visitors, Views, Conversions, WhatsApp Clicks

Top Products

Traffic Sources

Countries

Cities

Devices

Charts

Time Filters

---

## Settings

Business

Brand

Contact

SEO

Social Media

Team (Future)

Security

Danger Zone

---

## Admin Panel

Dashboard

Businesses

Users

Subscriptions

Categories

Themes

Reports

Moderation

Announcements

Support Tickets

Feature Flags

System Logs

---

# Security Blueprint

## Security Philosophy

Never trust client input.

Validate everything.

Authorize everything.

Log everything.

---

## Authentication

Email Verification

Remember Me

Password Reset

Session Management

Future: 2FA, Passkeys, Social Login

---

## Authorization

Laravel Policies

Gates

Role Based Access: Owner, Admin, Staff, Customer

Future: Granular Permissions

---

## Input Validation

Livewire Validation

Form Requests

Custom Rules

File Validation

Image Validation

URL Validation

Phone Validation

---

## CSRF Protection

Enabled on every Form and Action.

---

## XSS Protection

Escape all output

Sanitize HTML

Never trust rich text.

---

## SQL Injection

Use Eloquent

Parameterized Queries

No raw SQL unless necessary.

---

## Rate Limiting

Login

Password Reset

Search

API

Uploads

Contact Forms

---

## File Upload Security

Allowed Mime Types

Virus Scan (Future)

Image Optimization

Unique File Names

Private Storage

Signed URLs

---

## Business Isolation

Every query scoped by business_id

No cross tenant access. Ever.

---

## Audit Logs

Business Updates

Theme Changes

Subscription Changes

Admin Actions

Failed Logins

Permission Changes

Deleted Resources

---

## Backups

Daily Database

Media Backup

30 Day Retention

Disaster Recovery Plan

---

## Monitoring

Laravel Pulse

Laravel Telescope (Development)

Laravel Nightwatch (Future)

Error Tracking

Performance Monitoring

Queue Monitoring

---

## API Security

Sanctum

Rate Limits

Token Expiration

Scopes

Signed URLs

HTTPS Only

---

## Headers

HSTS

Content Security Policy

X-Frame Options

Referrer Policy

XSS Protection

---

# Payment & Subscription Architecture

## Model

Freemium SaaS

---

## Plans

### Free

One Business

Basic Theme

Limited Analytics

Basic SEO

Limited Media

Community Support

### Professional

Premium Themes

Advanced Analytics

Priority Search

More Media

SEO Tools

Marketing Links

Custom Branding

### Business

Everything in Professional

Multiple Staff

Advanced Reports

Priority Support

Future Integrations

### Enterprise (Future)

Unlimited Businesses

API Access

Custom Domains

White Label

Dedicated Support

---

## Supported Payment Providers

### Primary

Paystack

### Secondary

Flutterwave

### Future

Stripe

Lemon Squeezy

---

## Payment Flow

User → Choose Plan → Checkout → Webhook → Verify Payment → Create Subscription → Enable Features → Email Receipt

Never trust callback URLs. Always verify payments server-side.

---

## Subscription States

Trial

Active

Expired

Cancelled

Past Due

Suspended

---

## Billing Features

Upgrade

Downgrade

Renew

Cancel

Retry Failed Payment

Invoices

Payment History

Receipts

---

## Webhooks

Payment Success

Payment Failed

Subscription Renewed

Subscription Cancelled

Refund

Every webhook: Verify Signature, Log Event, Retry Failed Processing

---

## Future Revenue Features

Marketplace Promotion

Featured Listings

Sponsored Businesses

Priority Search Placement

Business Verification Fee

Theme Marketplace

AI Credits

Custom Domain Purchase

Booking Commission

---

# Future Roadmap

## Phase 1 (MVP)

Authentication

Business Profiles

Product Stores

Service Businesses

Themes

Explore

Search

Analytics

WhatsApp Integration

Subscriptions

## Phase 2

Verification

Reviews

Saved Businesses

Premium Themes

Marketing Links

QR Codes

Notifications

## Phase 3

Appointments

Lead Management

Teams

Custom Domains

Invoices

Advanced Reports

## Phase 4

AI Store Assistant

AI SEO

AI Product Descriptions

AI Search

Recommendations

Public API

Mobile Applications

Partner Integrations
