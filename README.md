# 📁 File Upload & Sharing API

A powerful and secure Laravel-based API to handle file uploads, sharing via download links, session management, and admin/user notifications. Designed for speed, scalability, and privacy.

---

## 🚀 Features

- 📤 File uploads with session tracking  
- 🔐 Secure and time-limited download links  
- 📩 Email notifications for users and admins  
- 👥 Guest and authenticated user support  
- 🧾 File metadata logging (IP, expiration, total size)  
- 🛡️ Admin dashboard integration for moderation  
- ⏱️ Asynchronous job dispatching and queue support  

---

## 🛠️ Technologies Used

- **Laravel **
- **MySQL **
- **Laravel Notifications (Mail, Database)**
- **Laravel Queues**
- **Blade Markdown Email Templates**

---

## 📦 Installation

```bash
git clone https://github.com/your-org/file-upload-api.git
cd DocShare
composer install
cp .env.example .env
php artisan key:generate
```

Then configure your `.env`:

```dotenv
Update the env.example with your configurations

```

Run migrations:

```bash
php artisan migrate:fresh --seed
```

---

## 📤 API Endpoints

| Method | Endpoint                     | Description                        | Auth Required |
|--------|------------------------------|------------------------------------|----------------|
| POST   | `/api/upload`                | Upload files and create session    | ✅ Yes         |
| GET    | `/api/download/{token}`      | Download shared files              | ❌ No          |
| GET    | `/api/session/{id}`          | Get session details                | ✅ Yes         |
| GET    | `/api/admin/uploads`         | Admin view of all uploads          | ✅ Admin       |

---

## 📧 Notifications

| Template              | Trigger                    | Recipient     |
|-----------------------|----------------------------|---------------|
| `email.uploaded`      | File uploaded (user)       | Uploader      |
| `email.uploaded_admin`| File uploaded (admin view) | Admin/Team    |

Customize templates in:
```
resources/views/email/
```

---

## 🧪 Testing

```bash
php artisan test
```

---

## 🧰 Developer Notes

- All file sessions are stored in `upload_sessions`.
- Files are associated using Eloquent relationships.
- File size formatting via `format_bytes()` helper.

---

## 👨‍💻 Contributing

1. Fork the repo  
2. Create a feature branch: `git checkout -b feature-name`  
3. Commit your changes: `git commit -am 'Add feature'`  
4. Push to the branch: `git push origin feature-name`  
5. Create a pull request  

---

## 📄 License

This project is open-sourced under the [MIT License](LICENSE).

---

## 🤝 Support

For any issues or feature requests, feel free to [open an issue](https://github.com/your-org/file-upload-api/issues) or contact us via [support@docshare.com](mailto:support@docshare.com).
