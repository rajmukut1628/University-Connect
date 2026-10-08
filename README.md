# 🎓 University Connect – University Community & Networking Platform

University Connect is a web-based university community platform designed to connect students, alumni, administrators, and mentors in one centralized environment.

The platform provides a secure space for academic networking, communication, mentorship, career opportunities, university news, and community engagement.

---

## 📌 Project Overview

University Connect brings together different members of a university community through a single platform.

The system enables students and alumni to connect with mentors, discover job opportunities, communicate with other members, follow university news, and participate in academic and professional networking.

---

## ✨ Key Features

### 👨‍🎓 Student

* Secure student login
* Student dashboard
* University newsfeed
* Create and interact with posts
* Ask AI
* Find and connect with mentors
* Send mentor requests
* Search job opportunities
* Messaging
* Manage personal profile
* View alumni and university community members

### 🎓 Alumni

* Secure alumni login
* Alumni dashboard
* University newsfeed
* Create and interact with posts
* Mentor students
* Manage mentor requests
* Search job opportunities
* Messaging
* Manage professional profile
* Connect with university community members

### 👨‍💼 Admin

* Admin dashboard
* Manage students
* Manage alumni
* Manage university news
* Manage posts and community content
* Manage mentors
* Manage jobs
* Manage users
* Monitor platform activities

### 👑 Super Admin

* Full system management
* Manage administrators
* Create new administrators
* Manage students and alumni
* Manage university content
* Manage jobs and mentors
* Manage system users
* Monitor overall platform activities

---

## 🧩 Main Modules

* Authentication & Authorization
* Student Management
* Alumni Management
* Admin Management
* Super Admin Management
* Newsfeed
* University News
* AI Assistant
* Mentor Management
* Mentor Requests
* Job Portal
* Messaging System
* User Profiles
* Role-Based Access Control

---

## 🔐 Security

University Connect uses role-based access control to ensure that users can only access the features and information permitted for their role.

### Security Features

* Secure authentication
* Role-based authorization
* Protected routes
* Admin and Super Admin access control
* User permission management
* Input validation
* Secure database operations
* Protected user information

---

## 👥 User Roles

| Role        | Main Responsibilities                                 |
| ----------- | ----------------------------------------------------- |
| Student     | Networking, newsfeed, mentors, jobs, messaging and AI |
| Alumni      | Networking, mentoring, jobs, newsfeed and messaging   |
| Admin       | Manage university platform content and users          |
| Super Admin | Complete system and administrator management          |

---

## 🏗️ System Architecture

```text
                    ┌─────────────────────┐
                    │      Users          │
                    │ Student / Alumni    │
                    │ Admin / Super Admin │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │     Frontend        │
                    │   Laravel Blade     │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │      Backend        │
                    │       Laravel       │
                    │        PHP          │
                    └──────────┬──────────┘
                               │
                               ▼
                    ┌─────────────────────┐
                    │       MySQL         │
                    │      Database       │
                    └─────────────────────┘
```

---

## 🛠️ Technologies Used

### Backend

* PHP
* Laravel

### Frontend

* HTML5
* CSS3
* JavaScript
* Bootstrap

### Database

* MySQL

### Development Tools

* XAMPP
* Composer
* Git
* GitHub
* Visual Studio Code

---

## 📂 Main Modules

```text
University Connect
│
├── Authentication
├── Student Management
├── Alumni Management
├── Admin Management
├── Super Admin Management
├── Newsfeed
├── University News
├── Ask AI
├── Mentor Management
├── Mentor Requests
├── Job Portal
├── Messaging
├── User Profiles
└── Role-Based Access Control
```

---

## 🔄 Mentor Connection Flow

```text
Student
   │
   │ Find Mentor
   ▼
Mentor Profile
   │
   │ Send Request
   ▼
Mentor
   │
   │ Accept / Reject
   ▼
Connection Established
   │
   ▼
Messaging & Mentorship
```

---

## 💼 Job Portal

University Connect provides a dedicated job platform where students and alumni can discover career opportunities.

Users can:

* Browse available jobs
* View job details
* Search job opportunities
* Explore career-related information
* Connect with the university community

---

## 💬 Messaging System

The messaging module allows students, alumni, and mentors to communicate within the platform.

Main features include:

* User-to-user messaging
* Mentor-related communication
* Conversation management
* Secure communication
* Role-based access

---

## 📰 Newsfeed

The newsfeed provides a centralized community space where users can:

* Create posts
* View community updates
* Interact with posts
* Share university-related information
* Engage with students and alumni

---

## 🤖 Ask AI

The Ask AI module provides an AI-powered assistant designed to help users with academic, career, and university-related questions.

---

## 🎯 Project Objectives

* Connect students and alumni through a centralized platform
* Build a stronger university community
* Provide mentorship opportunities
* Improve student-alumni networking
* Provide career and job opportunities
* Centralize university news and announcements
* Enable secure communication
* Provide AI-assisted academic and career support
* Create a modern digital university community

---

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone https://github.com/rajmukut791/University-Connect.git
```

### 2. Navigate to the Project

```bash
cd University-Connect
```

### 3. Install Dependencies

```bash
composer install
```

### 4. Configure Environment

Create a `.env` file:

```bash
cp .env.example .env
```

Configure the database:

```env
DB_DATABASE=university_connect
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Run Migrations

```bash
php artisan migrate
```

### 7. Start the Application

```bash
php artisan serve
```

---

## 📸 Screenshots

```markdown
![Welcome Page](screenshots/welcome.png)

![Student Dashboard](screenshots/student-dashboard.png)

![Alumni Dashboard](screenshots/alumni-dashboard.png)

![Newsfeed](screenshots/newsfeed.png)

![Mentor Management](screenshots/mentors.png)

![Job Portal](screenshots/jobs.png)

![Messaging](screenshots/messages.png)

![Admin Dashboard](screenshots/admin-dashboard.png)
```

---

## 🔮 Future Improvements

* Mobile application
* Advanced AI assistant
* Video mentoring
* Online events
* University clubs and organizations
* Advanced job recommendation system
* Real-time notifications
* Online career counseling
* University-wide event management
* Advanced analytics and reporting

---

## 📚 Academic Project

**Project Name:** University Connect
**Project Type:** University Community & Networking Platform
**Technology:** Laravel, PHP & MySQL
**Field:** Computer Science & Engineering

---

## 👨‍💻 Developer

**Raj Mukut**

Computer Science & Engineering
Northern University Bangladesh

### Connect

* GitHub: https://github.com/rajmukut1628
* Facebook: https://facebook.com/rajmukut791
* Email: [rajmukut791@gmail.com](mailto:rajmukut791@gmail.com)

---

## 📄 License

This project was developed for academic and educational purposes.

© 2026 Raj Mukut. All Rights Reserved.
