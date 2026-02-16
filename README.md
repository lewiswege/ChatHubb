# ChatHub - Omnichannel Messaging System

## What is ChatHub?

ChatHub is a customer messaging platform that helps businesses manage conversations from different channels (like WhatsApp, Telegram, SMS) all in one place. Think of it as a central inbox where support agents can respond to customers, no matter which platform the customer is using.

## What Can It Do Right Now?

### ✅ Working Features

**Conversation Management**
- View all customer conversations in a WhatsApp-style list
- See message previews, unread counts, and conversation status
- Chat with customers using Telegram-style message bubbles
- Track which channel each message came from
- Assign conversations to support agents

**Customer Management**
- Store customer information (name, phone, email)
- See conversation history for each customer
- Search customers by name, phone, or email

**Dashboard**
- See total conversations and active ones at a glance
- Monitor unread messages that need attention
- Track new customers joining today
- View today's message activity
- See conversations broken down by channel (pie chart)

**Message Simulator (Testing Tool)**
- Send and receive test messages without needing real WhatsApp or Telegram accounts
- Perfect for training staff or demonstrating the system
- Uses webhook to simulate incoming messages

**Smart Search**
- Press CTRL+K (or CMD+K on Mac) to search anything instantly
- Find conversations by customer name
- Search customers across all their info

**Modern UI**
- Violet/purple theme throughout
- Custom loading bar for smooth experience
- Responsive design that works on different screen sizes

### ⏳ Not Yet Implemented

**Real Messaging Channels**
- WhatsApp integration (requires WhatsApp Business API)
- Telegram integration (requires Telegram Bot API)
- SMS integration (requires SMS gateway)
- Other social platforms

**Real-Time Updates**
- Live message notifications without refreshing
- See when other agents are viewing the same conversation
- Typing indicators

## Important: How Messaging Works Right Now

**You can only send and receive messages through the "Simulator"** - this is a testing tool built into the system.

When you create a new conversation, you'll see options for WhatsApp, Telegram, and other channels. These options are there to prepare the system for future expansion, but right now:
- ✅ You can label a conversation as "WhatsApp" or "Telegram"
- ✅ The system will track which channel it should use
- ❌ You cannot actually send messages through real WhatsApp or Telegram yet

To send real messages through WhatsApp, Telegram, etc., those integrations need to be built and connected to their respective APIs.

## Getting Started

### Login Credentials
```
Email: admin@chathubb.test
Password: wenshenx
```

### First Time Setup

1. Start the application server:
   ```bash
   php artisan serve
   ```

2. Start the queue workers (needed to process messages):
   ```bash
   php artisan queue:work --tries=3
   ```

3. Open your browser and go to: `http://localhost:8000/admin`

4. Log in with the credentials above

### Testing Messages with the Simulator

To simulate receiving a message from a customer:

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

This will:
1. Create a customer with phone number +254791810187 (if they don't exist)
2. Create a new conversation
3. Add the message "Hello, I need help!" to the conversation
4. Mark the message as unread

You can then respond from the admin panel, and the reply will be saved in the system.

## How to Use the System

### Creating a New Conversation

1. Go to **Conversations** in the sidebar
2. Click **Create** button at the top right
3. Either:
   - Select an existing customer from the dropdown
   - Click the **+** button in the customer field to create a new customer
4. Choose the channel (remember: only Simulator works for now)
5. Click **Create**

### Responding to Messages

1. Click on any conversation from the list
2. Type your reply in the text box at the bottom
3. Click **Send Message**
4. Your message appears as a blue bubble on the right
5. Customer messages appear as gray bubbles on the left

### Managing Conversations

**Conversation Statuses:**
- **New** - Just arrived, hasn't been looked at yet
- **In Progress** - Agent is working on it
- **Waiting on Customer** - We responded, waiting for them to reply
- **Resolved** - Issue is solved
- **Closed** - Conversation is finished

You can change the status by clicking **Edit** on any conversation.

### Using the Dashboard

The dashboard shows you:
- **Total Conversations** - How many conversations you have overall
- **Unread Messages** - Messages that need your attention (shows in red if there are any)
- **Total Customers** - Your customer base, with today's new customers
- **Messages Today** - Today's message activity
- **Recent Conversations** - Last 5 conversations for quick access
- **Conversations by Channel** - Visual breakdown of which channels customers are using

## What's Under the Hood

For the developers on your team, here's what powers ChatHub:

- **Laravel 12** - Modern PHP framework
- **FilamentPHP v4** - Beautiful admin panel
- **MySQL** - Database for storing everything
- **Redis** - Fast caching and queue management
- **Queue System** - Processes messages in the background
- **ULID** - Unique, sortable IDs for records

## Future Expansion

The system is built to easily add new channels. When you're ready to connect real messaging platforms:

1. **WhatsApp Business API** - Requires business verification and Meta approval
2. **Telegram Bot API** - Requires creating a bot with BotFather
3. **SMS Gateway** - Requires provider like Twilio or similar
4. **Real-Time Updates** - Laravel Reverb for live message notifications

Each channel follows the same pattern, making it straightforward to add more platforms later.

## Support & Questions

If something isn't working:
1. Make sure queue workers are running (`php artisan queue:work`)
2. Check that the database is set up (`php artisan migrate`)
3. Clear the cache (`php artisan cache:clear`)

For errors, check the log files in `storage/logs/laravel.log`

## License

Built with Laravel framework - [MIT License](https://opensource.org/licenses/MIT)
