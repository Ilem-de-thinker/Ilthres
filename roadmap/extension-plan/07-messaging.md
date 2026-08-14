# 07 — Messaging & Communication

> Customers need to reach businesses. Businesses need to respond. Conversations are threaded.

## What Already Exists

- WhatsApp field on businesses (direct link)
- Contact page on themes
- Notification system exists
- Real-time: Livewire supports polling/websockets

## What This Phase Adds

### 1. Tables

```sql
CREATE TABLE conversations (
    id              BIGSERIAL PRIMARY KEY,
    business_id     BIGINT REFERENCES businesses(id) ON DELETE CASCADE,
    customer_id     BIGINT REFERENCES users(id) ON DELETE CASCADE,

    source          ENUM('website','whatsapp','booking','review','direct') DEFAULT 'direct',
    source_id       BIGINT,

    status          ENUM('open','closed','archived') DEFAULT 'open',
    priority        ENUM('low','normal','high','urgent') DEFAULT 'normal',

    last_message_at TIMESTAMP,
    created_at      TIMESTAMP DEFAULT NOW(),
    updated_at      TIMESTAMP DEFAULT NOW()
);

CREATE TABLE messages (
    id              BIGSERIAL PRIMARY KEY,
    conversation_id BIGINT REFERENCES conversations(id) ON DELETE CASCADE,
    sender_id       BIGINT REFERENCES users(id) ON DELETE SET NULL,
    sender_type     ENUM('customer','business_owner','system','ai') NOT NULL,

    content         TEXT NOT NULL,
    attachments     JSONB,

    read_at         TIMESTAMP,
    delivered_at    TIMESTAMP,

    created_at      TIMESTAMP DEFAULT NOW()
);
```

### 2. Files to Create

- Migrations for both tables
- Models: Conversation, Message
- Actions: StartConversation, SendMessage, CloseConversation, MarkAsRead
- DTOs: MessageData, ConversationData
- Events: ConversationStarted, MessageSent, MessageRead
- Listeners: NotifyBusinessOfMessage, NotifyCustomerOfMessage
- Policy: ConversationPolicy, MessagePolicy
- Livewire: InboxManager (merchant), ChatWidget (customer)
- Views: inbox-manager.blade.php, chat-widget.blade.php

### 3. Message Sources

Conversations can originate from:
- **website** — customer clicks "Contact" on business page
- **booking** — customer books appointment, conversation starts
- **review** — customer leaves review, business responds
- **whatsapp** — redirected from WhatsApp link
- **direct** — customer initiates from dashboard

### 4. Real-Time Updates

Use Livewire polling or Laravel Websockets for real-time messaging:
```php
// In InboxManager
public function polling(): int
{
    return $this->selectedConversation ? 2 : 5; // Faster when chat is open
}
```

### 5. Routes

- `/dashboard/inbox` — merchant inbox
- `/{slug}/contact` — public contact/chat widget (replaces or extends current contact page)

---

## Checklist

- [ ] Migration: `conversations` table
- [ ] Migration: `messages` table
- [ ] Models: Conversation, Message
- [ ] Actions: StartConversation, SendMessage, CloseConversation, MarkAsRead
- [ ] DTOs: MessageData, ConversationData
- [ ] Events + Listeners (wired in EventServiceProvider)
- [ ] Policy: ConversationPolicy, MessagePolicy
- [ ] Livewire: InboxManager (merchant)
- [ ] Livewire: ChatWidget (customer)
- [ ] Views: inbox-manager.blade.php, chat-widget.blade.php
- [ ] Routes: `/dashboard/inbox`, `/{slug}/contact`
