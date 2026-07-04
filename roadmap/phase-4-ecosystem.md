# Phase 4 — Ecosystem

> **Goal:** AI-powered tools, public API, mobile apps, and third-party integrations.  
> **Prerequisite:** Phase 3 complete  
> **Livewire Components:** ~10  
> **New Infrastructure:** Meilisearch, API routes, Mobile backend

---

## Features

### 1. AI Store Assistant

Chatbot on public business pages that answers customer questions about products, services, and hours.

**Livewire Components:**
| Component | Command |
|-----------|---------|
| `AI\StoreAssistant` | `php artisan make:livewire AI\\StoreAssistant` |
| `AI\AssistantSettings` | `php artisan make:livewire AI\\AssistantSettings` |

**Models:** `AiConversation`, `AiMessage`, `AiTrainingData` (with `--migration`)

**Actions:**
- `AI\ProcessQuery`
- `AI\TrainOnBusinessData`
- `AI\UpdateAssistantPersonality`

**Events:** `AssistantQueryProcessed`, `AssistantTrained`

**Jobs:** `TrainAssistantModel` — background training on business data

---

### 2. AI SEO

Automated SEO optimization — meta titles, descriptions, keywords, alt text.

**Actions:**
- `SEO\GenerateMetaTags`
- `SEO\OptimizeImages`
- `SEO\SuggestKeywords`

**Events:** `SeoOptimized`, `SeoScoreUpdated`

**Jobs:** `AnalyzeSeoScore` — periodic SEO audit
**Jobs:** `GenerateSitemap` — auto-updated sitemap

---

### 3. AI Product Descriptions

Generate professional product descriptions from key features.

**Actions:**
- `AI\GenerateDescription`
- `AI\GenerateVariations`

**Events:** `DescriptionGenerated`, `DescriptionApplied`

---

### 4. AI Search (Meilisearch)

Replace database search with Meilisearch for typo-tolerant, faceted, instant search.

**Actions:**
- `Search\IndexBusiness`
- `Search\IndexProduct`
- `Search\SearchAll`

**Jobs:** `SyncSearchIndex` — sync models to Meilisearch
**Jobs:** `RebuildSearchIndex` — full reindex

---

### 5. Recommendations

Personalized business and product recommendations based on browse history.

**Actions:**
- `Recommendations\GetForUser`
- `Recommendations\GetForBusiness`
- `Recommendations\TrackInteraction`

**Events:** `RecommendationsGenerated`

**Jobs:** `GenerateRecommendations` — batch compute recommendations

---

### 6. Public API (REST)

Public API for third-party integration. Authentication via Sanctum.

**Controllers:** (Domain-driven under `app/Http/Controllers/Api/`)
- `Api\BusinessController`
- `Api\ProductController`
- `Api\ServiceController`
- `Api\SearchController`
- `Api\AnalyticsController`

**Rate Limiting:** 60 req/min for free, 300 req/min for paid

**Documentation:** OpenAPI/Swagger spec at `/api/documentation`

**Tests:**
```
php artisan make:test Api\\BusinessApiTest
php artisan make:test Api\\ProductApiTest
php artisan make:test Api\\SearchApiTest
```

---

### 7. Mobile Applications

Flutter / React Native companion apps for merchants and customers.

**Backend API:** Ready from Public API above.

**Push Notifications:**
- `Notifications\SendPushNotification`
- Firebase Cloud Messaging integration

**Additional Queue Jobs:**
| Job | Queue | Purpose |
|-----|-------|---------|
| `SyncSearchIndex` | `search` | Index to Meilisearch |
| `RebuildSearchIndex` | `search` | Full reindex |
| `GenerateRecommendations` | `analytics` | Batch compute recs |
| `SendPushNotification` | `notifications` | Mobile push alerts |
| `TrainAssistantModel` | `ai` | Train on business data |
| `AnalyzeSeoScore` | `default` | Periodic SEO audit |

---

### 8. Partner Integrations

Third-party app marketplace and API connections.

**Models:** `Integration`, `WebhookEndpoint` (with `--migration`)

**Actions:**
- `Integrations\ConnectService`
- `Integrations\DisconnectService`
- `Integrations\SyncData`

**Events:** `IntegrationConnected`, `IntegrationDisconnected`, `WebhookReceived`

**Webhooks:** Outbound webhooks for partner event notifications.

---

## Database Tables (Phase 4)

| Table | Purpose |
|-------|---------|
| `ai_conversations` | Store assistant chat sessions |
| `ai_messages` | Individual chat messages |
| `ai_training_data` | Business-specific training data |
| `recommendations` | Cached recommendations |
| `integrations` | Connected third-party services |
| `webhook_endpoints` | Outbound webhook configs |

---

## Infrastructure Changes

| Change | Reason |
|--------|--------|
| Meilisearch server | Full-text search with typo tolerance |
| AI API keys (OpenAI/etc) | AI feature integration |
| Firebase project | Push notifications |
| CDN for media | Global media delivery |
| API subdomain (`api.ilthres.com`) | Public API endpoint |
