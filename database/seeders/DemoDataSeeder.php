<?php

namespace Database\Seeders;

use App\Enums\ChannelType;
use App\Enums\ContentType;
use App\Enums\ConversationStatus;
use App\Enums\MessageDirection;
use App\Enums\MessageStatus;
use App\Models\Conversation;
use App\Models\Customer;
use App\Models\CustomerChannelIdentifier;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create agent users
        $agent1 = User::firstOrCreate(
            ['email' => 'agent1@chathubb.test'],
            ['name' => 'Sarah Wilson', 'password' => bcrypt('password')]
        );

        $agent2 = User::firstOrCreate(
            ['email' => 'agent2@chathubb.test'],
            ['name' => 'Mike Johnson', 'password' => bcrypt('password')]
        );

        // Demo customers and conversations
        $demoData = [
            // 3 Simulator Conversations
            [
                'customer' => ['name' => 'John Smith', 'phone' => '+1234567890'],
                'channel' => ChannelType::SIMULATOR,
                'status' => ConversationStatus::IN_PROGRESS,
                'agent' => $agent1,
                'unread' => 2,
                'messages' => [
                    ['dir' => 'in', 'content' => 'Hi, my internet is not working', 'time' => now()->subHours(2)],
                    ['dir' => 'out', 'content' => 'Hi John! Let me check that for you.', 'time' => now()->subHours(2)->addMinutes(5)],
                    ['dir' => 'out', 'content' => 'I can see your modem is offline. Can you try unplugging it for 30 seconds?', 'time' => now()->subHours(2)->addMinutes(6)],
                    ['dir' => 'in', 'content' => 'Ok, I unplugged it and plugged it back in', 'time' => now()->subHour()],
                    ['dir' => 'in', 'content' => 'Still not working though', 'time' => now()->subMinutes(30)],
                ],
            ],
            [
                'customer' => ['name' => 'Sarah Davis', 'phone' => '+1444555666'],
                'channel' => ChannelType::SIMULATOR,
                'status' => ConversationStatus::WAITING_ON_CUSTOMER,
                'agent' => $agent1,
                'unread' => 0,
                'messages' => [
                    ['dir' => 'in', 'content' => 'Can I change my billing date?', 'time' => now()->subHours(5)],
                    ['dir' => 'out', 'content' => 'Yes! What date would you prefer?', 'time' => now()->subHours(5)->addMinutes(2)],
                    ['dir' => 'in', 'content' => 'The 15th of each month', 'time' => now()->subHours(5)->addMinutes(3)],
                    ['dir' => 'out', 'content' => 'Done! Your next bill will be on the 15th. Is there anything else I can help with?', 'time' => now()->subHours(4)],
                ],
            ],
            [
                'customer' => ['name' => 'Robert Taylor', 'phone' => '+1333222111'],
                'channel' => ChannelType::SIMULATOR,
                'status' => ConversationStatus::IN_PROGRESS,
                'agent' => $agent2,
                'unread' => 1,
                'messages' => [
                    ['dir' => 'in', 'content' => 'My bill seems higher than usual', 'time' => now()->subHours(3)],
                    ['dir' => 'out', 'content' => 'Let me review your account. One moment please.', 'time' => now()->subHours(3)->addMinutes(1)],
                    ['dir' => 'out', 'content' => 'I see you had some international calls this month. That added $45 to your bill.', 'time' => now()->subHours(3)->addMinutes(3)],
                    ['dir' => 'in', 'content' => 'Oh I see, can you show me the details?', 'time' => now()->subMinutes(15)],
                ],
            ],

            // 3 WhatsApp Conversations
            [
                'customer' => ['name' => 'Emily Chen', 'phone' => '+1987654321'],
                'channel' => ChannelType::WHATSAPP,
                'status' => ConversationStatus::RESOLVED,
                'agent' => $agent2,
                'unread' => 0,
                'messages' => [
                    ['dir' => 'in', 'content' => 'What are your business hours?', 'time' => now()->subDay()],
                    ['dir' => 'out', 'content' => 'We are open Monday-Friday 9AM-6PM, Saturday 10AM-4PM.', 'time' => now()->subDay()->addMinutes(3)],
                    ['dir' => 'in', 'content' => 'Perfect, thank you!', 'time' => now()->subDay()->addMinutes(5)],
                ],
            ],
            [
                'customer' => ['name' => 'David Martinez', 'phone' => '+1777888999'],
                'channel' => ChannelType::WHATSAPP,
                'status' => ConversationStatus::IN_PROGRESS,
                'agent' => $agent1,
                'unread' => 3,
                'messages' => [
                    ['dir' => 'in', 'content' => 'Hello! I placed an order yesterday but haven\'t received confirmation', 'time' => now()->subHours(6)],
                    ['dir' => 'out', 'content' => 'Hi David! Let me look that up for you. What\'s your order number?', 'time' => now()->subHours(6)->addMinutes(2)],
                    ['dir' => 'in', 'content' => '#ORD-12345', 'time' => now()->subHours(6)->addMinutes(3)],
                    ['dir' => 'out', 'content' => 'Found it! Your order is confirmed and will ship tomorrow. You should receive tracking info by email.', 'time' => now()->subHours(5)],
                    ['dir' => 'in', 'content' => 'Great! What carrier will you use?', 'time' => now()->subMinutes(45)],
                    ['dir' => 'in', 'content' => 'And estimated delivery time?', 'time' => now()->subMinutes(44)],
                    ['dir' => 'in', 'content' => 'Sorry for so many questions!', 'time' => now()->subMinutes(43)],
                ],
            ],
            [
                'customer' => ['name' => 'Jessica Anderson', 'phone' => '+1222333444'],
                'channel' => ChannelType::WHATSAPP,
                'status' => ConversationStatus::NEW,
                'agent' => null,
                'unread' => 2,
                'messages' => [
                    ['dir' => 'in', 'content' => 'Do you ship internationally?', 'time' => now()->subMinutes(10)],
                    ['dir' => 'in', 'content' => 'Specifically to Canada', 'time' => now()->subMinutes(9)],
                ],
            ],

            // 3 Telegram Conversations
            [
                'customer' => ['name' => 'Michael Brown', 'phone' => '+1555123456'],
                'channel' => ChannelType::TELEGRAM,
                'status' => ConversationStatus::NEW,
                'agent' => null,
                'unread' => 1,
                'messages' => [
                    ['dir' => 'in', 'content' => 'I need to upgrade my plan', 'time' => now()->subMinutes(5)],
                ],
            ],
            [
                'customer' => ['name' => 'Anna Kowalski', 'phone' => '+1666777888'],
                'channel' => ChannelType::TELEGRAM,
                'status' => ConversationStatus::RESOLVED,
                'agent' => $agent2,
                'unread' => 0,
                'messages' => [
                    ['dir' => 'in', 'content' => 'I forgot my password, can you help?', 'time' => now()->subHours(8)],
                    ['dir' => 'out', 'content' => 'Of course! I\'ll send you a password reset link to your email.', 'time' => now()->subHours(8)->addMinutes(1)],
                    ['dir' => 'out', 'content' => 'The link has been sent to a***@gmail.com. Please check your inbox.', 'time' => now()->subHours(8)->addMinutes(2)],
                    ['dir' => 'in', 'content' => 'Got it! Thanks so much', 'time' => now()->subHours(8)->addMinutes(5)],
                    ['dir' => 'out', 'content' => 'You\'re welcome! Let me know if you need anything else.', 'time' => now()->subHours(8)->addMinutes(6)],
                ],
            ],
            [
                'customer' => ['name' => 'Kevin Park', 'phone' => '+1999888777'],
                'channel' => ChannelType::TELEGRAM,
                'status' => ConversationStatus::IN_PROGRESS,
                'agent' => $agent1,
                'unread' => 1,
                'messages' => [
                    ['dir' => 'in', 'content' => 'Is there a student discount available?', 'time' => now()->subHours(1)],
                    ['dir' => 'out', 'content' => 'Yes! We offer 20% off for students. Do you have a valid student ID?', 'time' => now()->subHours(1)->addMinutes(3)],
                    ['dir' => 'in', 'content' => 'Yes I do! How do I verify it?', 'time' => now()->subMinutes(20)],
                    ['dir' => 'out', 'content' => 'You can upload a photo of your student ID or email us a scan at students@chathubb.test', 'time' => now()->subMinutes(18)],
                    ['dir' => 'in', 'content' => 'Perfect, sending it now!', 'time' => now()->subMinutes(2)],
                ],
            ],
        ];

        foreach ($demoData as $data) {
            // Create customer
            $customer = Customer::create([
                'name' => $data['customer']['name'],
                'phone_primary' => $data['customer']['phone'],
            ]);

            // Create channel identifier
            CustomerChannelIdentifier::create([
                'customer_id' => $customer->id,
                'channel' => $data['channel'],
                'identifier' => $data['customer']['phone'],
            ]);

            // Create conversation
            $lastMessageTime = collect($data['messages'])->last()['time'] ?? now();
            $conversation = Conversation::create([
                'customer_id' => $customer->id,
                'status' => $data['status'],
                'assigned_agent_id' => $data['agent']?->id,
                'last_message_at' => $lastMessageTime,
                'last_inbound_channel' => $data['channel'],
                'unread_count' => $data['unread'],
            ]);

            // Create messages
            foreach ($data['messages'] as $msg) {
                $isInbound = $msg['dir'] === 'in';

                Message::create([
                    'conversation_id' => $conversation->id,
                    'direction' => $isInbound ? MessageDirection::INBOUND : MessageDirection::OUTBOUND,
                    'channel' => $data['channel'],
                    'content_type' => ContentType::TEXT,
                    'content' => $msg['content'],
                    'status' => $isInbound ? MessageStatus::DELIVERED : MessageStatus::SENT,
                    'status_updated_at' => $msg['time'],
                    'sent_by_agent_id' => $isInbound ? null : $data['agent']?->id,
                    'created_at' => $msg['time'],
                    'updated_at' => $msg['time'],
                ]);
            }
        }

        $this->command->info('✅ Demo data created successfully!');
        $this->command->info('📊 Created '.Customer::count().' customers');
        $this->command->info('💬 Created '.Conversation::count().' conversations');
        $this->command->info('📨 Created '.Message::count().' messages');
    }
}
