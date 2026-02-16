<div align="center">

# 💬 ChatHub

### Omnichannel Messaging System

*Your unified inbox for customer conversations across all messaging platforms*

[![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![FilamentPHP](https://img.shields.io/badge/FilamentPHP-4.7-FDAE4B?style=for-the-badge&logo=data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDgiIGhlaWdodD0iNDgiIHZpZXdCb3g9IjAgMCA0OCA0OCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTI0IDQ4QzM3LjI1NDggNDggNDggMzcuMjU0OCA0OCAyNEM0OCAxMC43NDUyIDM3LjI1NDggMCAyNCAwQzEwLjc0NTIgMCAwIDEwLjc0NTIgMCAyNEMwIDM3LjI1NDggMTAuNzQ1MiA0OCAyNCA0OFoiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=)](https://filamentphp.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](https://opensource.org/licenses/MIT)

[Features](#-features) • [Getting Started](#-getting-started) • [Screenshots](#-screenshots) • [Documentation](#-documentation)

---

</div>

## 🌟 What is ChatHub?

ChatHub is a modern, beautiful customer messaging platform that brings all your customer conversations into one place. Whether your customers reach out via WhatsApp, Telegram, or SMS, ChatHub provides a unified interface for your support team to respond efficiently.

Think of it as **your team's mission control for customer communication** - no more juggling between different apps and platforms.

<div align="center">

### 🎯 Built for Support Teams | 💜 Modern UI | ⚡ Lightning Fast

</div>

---

## ✨ Features

<table>
<tr>
<td width="50%">

### 💬 Conversation Management
- 📱 WhatsApp-style conversation list
- 💬 Telegram-style message bubbles
- 👁️ Real-time unread counts
- 🏷️ Status tracking (New, In Progress, Resolved)
- 👥 Agent assignment

</td>
<td width="50%">

### 👥 Customer Management
- 📇 Complete customer profiles
- 📞 Phone & email tracking
- 🔍 Powerful search functionality
- 📊 Conversation history
- ⚡ Quick customer creation

</td>
</tr>
<tr>
<td>

### 📊 Analytics Dashboard
- 📈 Total conversations overview
- 🚨 Unread message alerts
- 👤 New customer tracking
- 📅 Daily message activity
- 🥧 Channel breakdown charts

</td>
<td>

### 🎨 Modern Experience
- 💜 Beautiful violet theme
- 🌓 Dark mode support
- ⚡ Custom loading animations
- 📱 Fully responsive design
- ⌨️ Keyboard shortcuts (CTRL+K)

</td>
</tr>
</table>

---

## 🚀 Getting Started

### 📋 Prerequisites

```bash
PHP 8.3+
MySQL 8.0+
Redis
Composer
```

### ⚙️ Installation

1️⃣ **Clone the repository**
```bash
git clone https://github.com/lewiswege/ChatHubb.git
cd ChatHubb
```

2️⃣ **Install dependencies**
```bash
composer install
```

3️⃣ **Setup environment**
```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```

4️⃣ **Start the application**
```bash
# Terminal 1: Application Server
php artisan serve

# Terminal 2: Queue Worker
php artisan queue:work --tries=3
```

5️⃣ **Access the admin panel**

🌐 Open: `http://localhost:8000/admin`

🔐 **Login Credentials:**
- **Email:** `admin@chathubb.test`
- **Password:** `wenshenx`

---

## 🧪 Testing with the Simulator

The Simulator lets you test the system without connecting real messaging platforms.

**Send a test message:**

```bash
curl -X POST http://localhost:8000/webhooks/simulator \
-H "Content-Type: application/json" \
-d '{
  "from": "+254791810187",
  "message": "Hello, I need help!",
  "type": "text",
  "message_id": "test_001",
  "timestamp": "2026-02-16T10:00:00Z"
}'
```

✅ This creates a customer, starts a conversation, and adds the message - all ready for you to respond!

---

## 💡 How It Works

<div align="center">

```mermaid
graph LR
    A[Customer Messages] --> B[Webhook]
    B --> C[Queue Processing]
    C --> D[ChatHub Dashboard]
    D --> E[Agent Response]
    E --> F[Customer Receives Reply]

    style A fill:#8b5cf6,color:#fff
    style D fill:#8b5cf6,color:#fff
    style F fill:#8b5cf6,color:#fff
```

</div>

1. **Customer sends message** via any channel (WhatsApp, Telegram, etc.)
2. **Webhook receives** the message and queues it for processing
3. **Background worker** processes message and updates the dashboard
4. **Agent responds** through the beautiful ChatHub interface
5. **Message is sent** back to the customer on their preferred channel

---

## 🎯 Current Status

### ✅ Fully Working

| Feature | Status | Description |
|---------|--------|-------------|
| 💬 Conversation UI | ✅ Live | WhatsApp-style list with message previews |
| 👥 Customer Management | ✅ Live | Full CRUD with search and profiles |
| 📊 Dashboard | ✅ Live | Analytics, stats, and charts |
| 🔍 Global Search | ✅ Live | CTRL+K instant search |
| 🧪 Message Simulator | ✅ Live | Test without real accounts |
| 💜 Modern UI | ✅ Live | Violet theme with animations |

### ⏳ Planned Features

| Feature | Status | Description |
|---------|--------|-------------|
| 📱 WhatsApp Integration | 🔄 Planned | Real WhatsApp Business API |
| ✈️ Telegram Integration | 🔄 Planned | Telegram Bot API |
| 📲 SMS Gateway | 🔄 Planned | Twilio/similar integration |
| ⚡ Live Updates | 🔄 Planned | Real-time with Laravel Reverb |
| 💬 Typing Indicators | 🔄 Planned | See when customers are typing |

---

## 📚 Documentation

### 🎨 Usage Guide

<details>
<summary><b>Creating Conversations</b></summary>

1. Navigate to **Conversations** in the sidebar
2. Click the **Create** button
3. Select existing customer or create new with **+** button
4. Choose channel (currently Simulator only)
5. Start chatting!

</details>

<details>
<summary><b>Managing Conversations</b></summary>

**Status Flow:**
```
New → In Progress → Waiting on Customer → Resolved → Closed
```

Change status anytime by clicking **Edit** on a conversation.

</details>

<details>
<summary><b>Dashboard Widgets</b></summary>

- **Total Conversations**: Overall conversation count with active badge
- **Unread Messages**: Alert count (red when > 0)
- **Total Customers**: Customer base with today's additions
- **Messages Today**: Daily activity metrics
- **Recent Conversations**: Quick access to last 5 chats
- **Channel Breakdown**: Visual pie chart of channel distribution

</details>

### 🛠️ Tech Stack

<div align="center">

| Layer | Technology | Purpose |
|-------|-----------|---------|
| **Backend** | Laravel 12 | PHP framework |
| **Admin Panel** | FilamentPHP v4 | Beautiful admin interface |
| **Database** | MySQL | Data persistence |
| **Cache/Queue** | Redis | Fast caching & job processing |
| **Frontend** | Alpine.js | Reactive components |
| **Styling** | Tailwind CSS | Utility-first CSS |

</div>

### 🏗️ Architecture

```
app/
├── Actions/          # Business logic (ProcessInboundMessage, SendMessage)
├── Channels/         # Channel abstraction layer
│   ├── Contracts/    # ChannelDriverInterface
│   ├── Drivers/      # SimulatorDriver (+ future WhatsApp, Telegram)
│   └── ValueObjects/ # InboundMessage, ChannelResponse
├── Enums/            # Type-safe enums (ChannelType, MessageStatus)
├── Filament/         # Admin panel resources
│   ├── Resources/    # ConversationResource, CustomerResource
│   ├── Widgets/      # Dashboard widgets
│   └── Pages/        # Custom pages
├── Jobs/             # Queue jobs for async processing
└── Models/           # Eloquent models with ULID keys
```

---

## 🚨 Important Note

> **Currently, only the Simulator driver is implemented.** When you select WhatsApp or Telegram in the UI, the system will track the channel label, but actual messaging through those platforms requires additional API integrations.
>
> This design allows you to:
> - ✅ Test the complete workflow with the Simulator
> - ✅ Prepare data structure for real channels
> - ✅ Demo the system to stakeholders
> - ❌ Send actual WhatsApp/Telegram messages (yet!)

---

## 🤝 Contributing

Contributions are welcome! This project follows standard Laravel conventions.

---

## 📝 License

Built with Laravel framework - [MIT License](https://opensource.org/licenses/MIT)

---

<div align="center">

### 🌟 Made with Laravel, FilamentPHP, and lots of ☕

**[⬆ Back to Top](#-chathubb)**

</div>
