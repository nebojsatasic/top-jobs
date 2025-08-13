# 💼 Top Jobs

## 📄 Project Overview

This is a Laravel-based web application that connects job seekers with employers in a streamlined and user-friendly way. Job seekers can create profiles, browse and filter job listings, and apply directly for positions. Employers can post job vacancies, manage listings, and review applications.

The platform includes features such as advanced job filtering, email notifications, and applicant tracking to simplify the hiring process. It leverages Laravel's core features — including routing, authentication, authorization, queues (for sending emails), and database management — to deliver a maintainable and performant application.

## ✨ Features

### 🔐 Authentication & User Roles

- User registration and login for two roles: **Employers (companies)** and **Job Seekers**
- **Email verification** required upon registration
- **Resend verification email** for unverified accounts
- Role-based dashboards and access control
- Profile management for both user types

### 📁 Job Seeker Features

- Create and update personal profiles
- **Upload and manage resumes**
- Browse and **filter job listings**
- View individual job details
- **Apply directly** to jobs
- Track list of applied jobs
- **Receive email notifications** when shortlisted

### 🏢 Employer Features

- One-week **trial period** for new employers
- Employers can **subscribe** to premium plans:
  - **Weekly**
  - **Monthly**
  - **Yearly**
- Secure **Stripe payment integration**
- Prevents duplicate payments from active subscribers
- Receive **email confirmation** after successful payment
- Premium employers can:
  - **Post**, **edit**, and **delete** job listings
  - View all published jobs
  - View number of applicants for each job
  - View applicant profiles and **download resumes**
  - **Shortlist applicants**, with visual highlights and email alerts to candidates

### 🧠 Job Management

- Display **company profiles** with all published job listings
- **Advanced job filtering** (by salary, date and job type)
- Application tracking system for both employers and job seekers
- Email notification system for key interactions (e.g., purchase confirmation and details)

 ## 💳 Test Payments

This application uses **Stripe** in test mode. No real charges are made.

To test a **successful payment**, use the following test card details:

- **Card Number:** `4242 4242 4242 4242`
- **Expiration Date:** Any future date (e.g., `12/34`)
- **CVC:** Any 3 digits (e.g., `123`)
- **Email:** Any email (e.g., `test@example.com`)
- **Cardholder name:** Any name (e.g., `John Doe`)
- **Country or region:** Any country can be selected

## 🛠️ Technologies Used

- **Laravel 10** – PHP backend framework
- **Bootstrap 5** – Frontend framework for responsive design
- **SB Admin Theme** – Used for employer-facing features (dashboard, job posting, editing, displaying applicants, etc.)
- **Stripe (Test Mode)** – Payment processing
- **MySQL** – Database

## 🛠️ Installation

Follow these steps to run the application locally:

### 1. Clone the Repository

```bash
git clone https://github.com/nebojsatasic/top-jobs.git
cd top-jobs
```

### 2. Install PHP Dependencies

```bash
composer install
```

If you're having issues running the application (especially due to PHP version differences), you may need to update the dependencies:

> ```bash
> composer update
> ```
> ...to re-resolve dependencies for your environment.

### 3. Set Up the Environment File

Copy `.env.example` to `.env`

### 4. Generate the Application Key

```bash
php artisan key:generate
```

### 5. Configure MySQL Database

To connect your Laravel application to a MySQL database, update the `.env` file in the root directory with your database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_user
DB_PASSWORD=your_database_password
```

### 6. Run Database Migrations

```bash
php artisan migrate
```

### 7. Recreate Storage Symbolic Link

```bash
php artisan storage:link
```

### 8. Queue Configuration

To enable queued jobs using the **database** driver, update the `QUEUE_CONNECTION` environment variable in your `.env` file:

```env
QUEUE_CONNECTION=database
```

### 9. Start the Queue Worker

```bash
php artisan queue:work
```

### 10. Start the Local Development Server

```bash
php artisan serve
```

Access the application at: `http://127.0.0.1:8000`

## 🌐 Live Demo

Check out the live demo of the website [here](https://top-jobs.nebojsatasic.com).

## 📄 License

No license

## 👤 Author

Name: Nebojsa Tasic

Email: [nele.tasic@gmail.com](mailto:nele.tasic@gmail.com)

Website: [https://nebojsatasic.com](https://nebojsatasic.com)

Feel free to reach out if you need login details or any other information.
