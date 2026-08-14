# 10 — AI Integrations

> AI enhances the platform at multiple touchpoints. The full AI website builder comes later.

## What Already Exists

- No AI integrations yet
- Business creation collects: name, description, category, logo, cover
- Theme selection happens during onboarding
- Services/Products have manual descriptions

## AI Touchpoints (Phased)

### Phase A: AI Content Generation (This Phase)

Generate text content for businesses using AI. NOT the full website builder — that comes later.

#### 1. AI Generations Table

```sql
CREATE TABLE ai_generations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    user_id         BIGINT REFERENCES users(id) ON DELETE SET NULL,

    type            VARCHAR(50) NOT NULL,   -- tagline, about, description, seo, faq, service_desc
    prompt          TEXT,
    content         TEXT,
    content_type    VARCHAR(50),            -- text, json

    model_used      VARCHAR(50),
    tokens_used     INTEGER,
    cost            DECIMAL(10, 6),

    status          ENUM('pending','completed','failed') DEFAULT 'pending',
    error_message   TEXT,

    created_at      TIMESTAMP DEFAULT NOW()
);
```

#### 2. AI Prompts Table

```sql
CREATE TABLE ai_prompts (
    id              BIGSERIAL PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    type            VARCHAR(50) NOT NULL,
    template        TEXT NOT NULL,
    variables       JSONB,   -- ["business_name", "category", "location"]

    is_active       BOOLEAN DEFAULT TRUE,
    created_at      TIMESTAMP DEFAULT NOW()
);
```

#### 3. Seed Default Prompts

```php
// database/seeders/AIPromptSeeder.php
$prompts = [
    [
        'name' => 'Business Tagline',
        'type' => 'tagline',
        'template' => 'Generate a short, catchy tagline for a {{type}} business called "{{name}}" in {{city}}. Max 10 words. Return only the tagline.',
        'variables' => ['name', 'type', 'city'],
    ],
    [
        'name' => 'About Us',
        'type' => 'about',
        'template' => 'Write a professional "About Us" paragraph for a {{type}} business called "{{name}}" located in {{city}}, {{state}}. 3-4 sentences. SEO-friendly. Include the business name naturally.',
        'variables' => ['name', 'type', 'city', 'state'],
    ],
    [
        'name' => 'Service Description',
        'type' => 'service_description',
        'template' => 'Write a brief, compelling description for a service called "{{name}}" offered by a {{type}} business. 2-3 sentences. Include pricing context if price is "{{price}}".',
        'variables' => ['name', 'type', 'price'],
    ],
    [
        'name' => 'Product Description',
        'type' => 'product_description',
        'template' => 'Write a compelling product description for "{{name}}" sold by a {{type}} business. Price: {{price}}. 2-3 sentences. Highlight benefits.',
        'variables' => ['name', 'type', 'price'],
    ],
    [
        'name' => 'SEO Meta Description',
        'type' => 'seo_meta',
        'template' => 'Write a search engine meta description (max 160 chars) for a {{type}} business called "{{name}}" in {{city}}. Include key services.',
        'variables' => ['name', 'type', 'city'],
    ],
    [
        'name' => 'FAQ Generation',
        'type' => 'faq',
        'template' => 'Generate 5 frequently asked questions and answers for a {{type}} business called "{{name}}" in {{city}}. Format as JSON array: [{"q":"...","a":"..."}]',
        'variables' => ['name', 'type', 'city'],
    ],
];
```

#### 4. Service: AIPromptService

```php
// app/Services/AIPromptService.php

class AIPromptService
{
    public function generate(string $type, array $variables): string
    {
        $prompt = AIPrompt::where('type', $type)->where('is_active', true)->first();

        // Fill variables in template
        $filledPrompt = $prompt->template;
        foreach ($variables as $key => $value) {
            $filledPrompt = str_replace("{{{$key}}}", $value, $filledPrompt);
        }

        // Call AI (OpenAI, Claude, etc.)
        $response = $this->callAI($filledPrompt);

        // Log generation
        AIGeneration::create([...]);

        return $response;
    }
}
```

#### 5. Jobs

```php
// app/Jobs/GenerateBusinessContent.php
class GenerateBusinessContent implements ShouldQueue
{
    public function handle(AIPromptService $ai): void
    {
        // Generate tagline
        $tagline = $ai->generate('tagline', [...]);

        // Generate about
        $about = $ai->generate('about', [...]);

        // Generate SEO
        $seo = $ai->generate('seo_meta', [...]);

        // Generate FAQ
        $faq = $ai->generate('faq', [...]);

        // Save to business/website
    }
}
```

#### 6. Integration Points

- **Onboarding:** After business creation, dispatch `GenerateBusinessContent` job (optional — user can skip)
- **Service/Product creation:** "Generate description with AI" button in ServiceForm/ProductForm
- **Website pages:** AI can generate section content for hero, about, FAQ
- **Dashboard:** "AI Assistant" section showing generations, regenerate, edit, approve

#### 7. Livewire: AIContentEditor

- Shows AI-generated content for business
- Regenerate button (calls AI again)
- Edit inline
- Approve/save
- History of generations

### Phase B: AI Website Builder (Future)

> Full AI website generation — selects theme, creates pages, populates all sections, generates SEO.
> This is the "sign up → website in 30 seconds" feature from proc.md.
> Deferred until the website infrastructure (Phase 1) and pages/sections (Phase 2) are complete.

### Phase C: AI Search (Future)

> Natural language search: "I need a mechanic that repairs Toyota under ₦30,000 today"
> Parses intent, extracts category/price/availability, searches Meilisearch.
> Deferred until Meilisearch integration (Phase 9) is complete.

---

## Checklist

- [ ] Migration: `ai_generations` table
- [ ] Migration: `ai_prompts` table
- [ ] Models: AIGeneration, AIPrompt
- [ ] Seeder: AIPromptSeeder (6 default prompts)
- [ ] Service: AIPromptService
- [ ] Job: GenerateBusinessContent
- [ ] Livewire: AIContentEditor
- [ ] View: ai-content-editor.blade.php
- [ ] Integrate into ServiceForm (generate description button)
- [ ] Integrate into ProductForm (generate description button)
- [ ] Integrate into website section editor (generate content)
- [ ] Config: `openai.api_key` or `ai.provider` in config/services.php
