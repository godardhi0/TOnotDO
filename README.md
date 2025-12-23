# TOnotDO - Task Management App

A collaborative real-time task management application built with PHP, MySQL, and Node.js Socket.IO.

## Features

- **User Roles**: Client (creates tasks), Worker (completes tasks), Root/Admin (manages all)
- **Real-time Chat**: AI-powered chatbot for instant support
- **Real-time Task Updates**: All users see changes instantly via WebSockets
- **Task Management**: Create, assign, update, and mark tasks as complete
- **User Profiles**: Manage profile information and change passwords

## Tech Stack

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML, CSS, JavaScript
- **Real-time**: Node.js + Socket.IO
- **Server**: Apache (XAMPP)

## Installation

### 1. Clone the Repository

```bash
git clone <repository-url>
cd TOnotDO
```

### 2. Configure Environment Variables

Copy `.env.example` to `.env` and update with your settings:

```bash
cp .env.example .env
```

Edit `.env`:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=your_password
DB_NAME=tonotdo_app
NODE_SERVER_URL=http://localhost:3000
APP_URL=http://localhost/TOnotDO/public
```

### 3. Create Database

Create MySQL database and import schema:

```sql
CREATE DATABASE tonotdo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE tonotdo_app;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'worker', 'root') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    client_id INT NOT NULL,
    worker_id INT,
    status ENUM('pending', 'in-progress', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (worker_id) REFERENCES users(id)
);
```

### 4. Start Node.js Server

In `node-server/` directory:

```bash
cd node-server
npm install
node server.js
```

The server should print: `✅ Node.js server running on port 3000`

### 5. Access the App

Open your browser and go to:

```
http://localhost/TOnotDO/public/
```

## Default Test Users

After running the database schema, you can register new users or use:

- **Client**: Create and track task requests
- **Worker**: Complete assigned tasks
- **Root/Admin**: Manage users and all tasks

## Project Structure

```
TOnotDO/
├── app/
│   ├── controllers/       # Route handlers
│   ├── core/              # Database, Router, Auth
│   ├── models/            # User, Task models
│   └── views/             # HTML templates
├── node-server/           # Socket.IO server
├── public/                # Entry point (index.php)
├── .env.example           # Environment template
├── .gitignore             # Git exclusions
└── README.md              # This file
```

## Security

- ✅ Environment variables for credentials
- ✅ PDO prepared statements (SQL injection protection)
- ✅ Password hashing with `PASSWORD_DEFAULT`
- ✅ Session-based authentication
- ✅ Role-based access control

## API Routes

### Authentication

- `GET /auth/login` — Login form
- `POST /auth/login` — Process login
- `GET /auth/register` — Register form
- `POST /auth/register` — Create account
- `GET /auth/logout` — Logout

### Tasks

- `GET /tasks/myRequests` — Client's created tasks
- `GET /tasks/myTasks` — Worker's assigned tasks
- `GET /tasks/manage` — Root's all tasks
- `POST /tasks/create` — Create task
- `GET /tasks/edit/:id` — Edit task form
- `POST /tasks/update/:id` — Update task
- `GET /tasks/delete/:id` — Delete task
- `GET /tasks/complete/:id` — Mark as done

### Chat

- `GET /chat/index` — Chat interface

## Real-time Features

### Socket.IO Events

**Client → Server:**
- `chatMessage` — Send chat message
- `taskUpdate` — Task state change

**Server → Client:**
- `chatMessage` — Receive chat/bot reply
- `taskUpdate` — Broadcast task changes

## Troubleshooting

### Database connection fails

Check `.env` file credentials match your MySQL setup:

```bash
mysql -u root -p tonotdo_app
```

### Socket.IO connection refused

Ensure Node.js server is running on port 3000:

```bash
netstat -ano | findstr :3000
```

### Chat not working

- Check browser console for Socket.IO connection errors
- Verify Node.js server is running
- Check CORS settings in `node-server/server.js`

## Deployment

Before deploying to production:

1. ✅ Copy `.env.example` to `.env`
2. ✅ Update `.env` with production credentials
3. ✅ Set `APP_ENV=production` in `.env`
4. ✅ Ensure `.env` is in `.gitignore` (not tracked)
5. ✅ Run Node.js server in production mode (PM2, Docker, etc.)
6. ✅ Use HTTPS for production
7. ✅ Set secure cookie flags in production

## License

MIT

## Support

For issues, open a GitHub issue or contact support.
