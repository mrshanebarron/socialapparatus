<p align="center">
  <img src="https://socialapparatus.com/images/logo.svg" width="120" alt="SocialApparatus Logo">
</p>

<h1 align="center">SocialApparatus</h1>

<p align="center">
  <strong>A powerful, self-hosted social network platform built on Laravel</strong>
</p>

<p align="center">
  <a href="https://github.com/mrshanebarron/socialapparatus/blob/main/LICENSE"><img src="https://img.shields.io/badge/license-MIT-blue.svg" alt="License"></a>
  <img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?logo=livewire" alt="Livewire 3">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?logo=php" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/Tailwind-3.x-38B2AC?logo=tailwindcss" alt="Tailwind CSS">
</p>

<p align="center">
  <a href="https://socialapparatus.com">Website</a> •
  <a href="https://socialapparatus.com/docs">Documentation</a> •
  <a href="https://community.socialapparatus.com">Live Demo</a> •
  <a href="https://github.com/mrshanebarron/socialapparatus/issues">Issues</a>
</p>

---

## About

SocialApparatus is a complete, self-hosted social networking platform that gives you all the features of major social networks while keeping you in full control of your data. Built on the **TALL stack** (Tailwind CSS, Alpine.js, Laravel, Livewire), it's designed for developers who want a solid foundation for building community-driven applications.

## Features

### Core Social Features
- **📰 Activity Feed** - Real-time posts with rich media, polls, and location tagging
- **👥 Social Connections** - Follow system, friend requests, and blocking
- **💬 Messaging** - Real-time direct messages with typing indicators and reactions
- **🔔 Notifications** - Push notifications with customizable preferences
- **👤 User Profiles** - Customizable profiles with cover photos, bios, and custom fields

### Content & Media
- **📸 Media Galleries** - Photo albums with tagging and collaborative albums
- **📝 Blog/Articles** - Long-form content with rich text editing
- **📖 Stories** - 24-hour ephemeral content with interactive elements (polls, questions, quizzes)
- **🎬 Watch Parties** - Synchronized video watching with friends
- **🎵 Soundbites** - Short audio clips with duet capabilities

### Community Features
- **👥 Groups** - Public, private, and secret groups with moderation tools
- **📅 Events** - Event creation with ticketing, RSVPs, and reminders
- **📋 Collaborative Boards** - Kanban-style boards for team collaboration
- **💰 Fundraisers** - Community fundraising campaigns
- **🛒 Marketplace** - Buy and sell within your community

### Engagement Features
- **❤️ Reactions** - Multiple reaction types beyond simple likes
- **💬 Threaded Comments** - Nested comment threads with mentions
- **📊 Polls** - Interactive polls with real-time results
- **🏷️ Hashtags** - Discoverable content through hashtags
- **🔍 Global Search** - Search users, posts, groups, and more

### Advanced Features
- **✅ Verification System** - Verified badges with document verification
- **🛡️ Moderation Queue** - AI-assisted content moderation
- **📊 Admin Analytics** - Platform metrics and user insights
- **🎁 Virtual Gifts** - Send virtual gifts with coin system
- **📧 Weekly Digests** - Automated email summaries
- **🖼️ Profile Frames** - Customizable profile picture frames
- **🎖️ Badges** - Achievement and recognition system
- **👻 Memories** - "On This Day" feature for past content
- **✨ Avatar Builder** - Custom avatar creation

### Security & Privacy
- **🔐 Two-Factor Authentication** - TOTP-based 2FA
- **📱 Trusted Devices** - Device management and login alerts
- **🔒 Privacy Controls** - Granular privacy settings per post
- **👁️ Activity Log** - Full audit trail of account activity
- **🚫 Block & Restrict** - User blocking and restricted lists

## Tech Stack

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** Livewire 3, Alpine.js, Tailwind CSS
- **Database:** MySQL 8.0+ / PostgreSQL 14+
- **Authentication:** Laravel Jetstream with Fortify
- **Real-time:** Laravel Echo, Pusher/Soketi

## Requirements

- PHP 8.2 or higher
- Composer 2.x
- Node.js 18+ & NPM
- MySQL 8.0+ or PostgreSQL 14+
- Redis (optional, for caching/queues)

## Installation

### 1. Clone the repository

```bash
git clone https://github.com/mrshanebarron/socialapparatus.git
cd socialapparatus
```

### 2. Install dependencies

```bash
composer install
npm install
```

### 3. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure your database

Edit `.env` and set your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=socialapparatus
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run migrations and seeders

```bash
php artisan migrate
php artisan db:seed
```

### 6. Build assets

```bash
npm run build
```

### 7. Start the server

```bash
php artisan serve
```

Visit `http://localhost:8000` to access the web installer.

## Configuration

### Mail Configuration

For email notifications and verification:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### File Storage

For media uploads, configure your preferred storage:

```env
FILESYSTEM_DISK=public
# Or for S3:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=your-key
# AWS_SECRET_ACCESS_KEY=your-secret
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=your-bucket
```

### Real-time Features

For real-time notifications and messaging:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1
```

## Deployment

### Production Checklist

1. Set `APP_ENV=production` and `APP_DEBUG=false`
2. Configure a proper database server
3. Set up SSL/HTTPS
4. Configure queue workers for background jobs
5. Set up Redis for caching and sessions
6. Configure proper file permissions
7. Set up automated backups

### Queue Workers

For background job processing:

```bash
php artisan queue:work --daemon
```

Or use Supervisor for production:

```ini
[program:socialapparatus-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/socialapparatus/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=2
```

## Documentation

Full documentation is available at [socialapparatus.com/docs](https://socialapparatus.com/docs)

- [Getting Started](https://socialapparatus.com/docs/getting-started)
- [Installation Guide](https://socialapparatus.com/docs/installation)
- [Configuration](https://socialapparatus.com/docs/configuration)
- [Features Overview](https://socialapparatus.com/docs/features)
- [Theming](https://socialapparatus.com/docs/theming)
- [API Reference](https://socialapparatus.com/docs/api-reference)
- [Deployment](https://socialapparatus.com/docs/deployment)

## Contributing

Contributions are welcome! Please read our contributing guidelines before submitting a pull request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover a security vulnerability, please send an email to security@socialapparatus.com. All security vulnerabilities will be promptly addressed.

## License

SocialApparatus is open-sourced software licensed under the [MIT license](LICENSE).

---

<p align="center">
  Built with ❤️ by <a href="https://sbarron.com">Shane Barron</a>
</p>
