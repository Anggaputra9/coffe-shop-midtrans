# ☕ Artisan Specialty Coffee & Roastery

Aplikasi web e-commerce coffee shop dengan Midtrans Snap API (Sandbox).

## Stack
- **Backend:** Laravel 12
- **Frontend:** Blade + Tailwind CSS 4 + Alpine.js 3
- **Payment:** Midtrans Snap API (Sandbox)
- **Database:** SQLite (default) / MySQL

---

## Setup & Installation

```bash
# 1. Install dependencies
composer install
npm install

# 2. Setup environment
cp .env.example .env
php artisan key:generate

# 3. Configure Midtrans (edit .env)
# MIDTRANS_SERVER_KEY=SB-Mid-server-xxxxx
# MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxxx
# Get keys from: https://dashboard.sandbox.midtrans.com/settings/config

# 4. Run migrations & seed
php artisan migrate:fresh --seed

# 5. Start development server
composer dev
# Or manually:
php artisan serve
npm run dev
```

---

## Midtrans Sandbox Configuration

1. Buat akun di [Midtrans Sandbox](https://dashboard.sandbox.midtrans.com)
2. Ambil **Server Key** dan **Client Key** dari Settings > Access Keys
3. Masukkan ke `.env`

### Testing Webhook Lokal dengan Ngrok

```bash
# 1. Install ngrok: https://ngrok.com/download
# 2. Expose local server
ngrok http 8000

# 3. Copy HTTPS URL (misal https://abc123.ngrok.io)
# 4. Di Midtrans Dashboard > Settings > Configuration
#    Set Payment Notification URL: https://abc123.ngrok.io/api/midtrans/webhook
# 5. Update APP_URL di .env
```

### Test Card Numbers (Sandbox)

| Card Number          | Scenario          |
|----------------------|-------------------|
| 4811 1111 1111 1114  | Success (no 3DS)  |
| 4911 1111 1111 1113  | Success (3DS)     |
| 4411 1111 1111 1118  | Denied            |

- **Expiry:** Any future date
- **CVV:** 123

---

## Fitur Keamanan Webhook
- SHA-512 Signature Verification
- Database Transaction with lockForUpdate()
- Idempotency Check (skip final-status orders)
- CSRF exemption for webhook endpoint
- Comprehensive logging
