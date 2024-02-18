# MailerLite Test Assessment API

This is a simple PHP-based API for managing subscribers. The application includes endpoints for writing and retrieving subscribers, along with a basic Vue page for interaction.

## Getting Started

Follow the steps below to set up and run the application locally.

### Prerequisites

Make sure you have the following installed on your machine:

- PHP 8.x
- MySQL 5.x
- Composer
- Redis (for caching)
- RabbitMQ (for queuing)

### Clone the Repository

```bash
git clone https://github.com/your-username/mailerlite-api.git
cd mailerlite-api
```
### Copy the .env.example File
```bash
cp .env.example .env
```

### Update the .env File

Open the .env file in a text editor and update the database, Redis, and RabbitMQ credentials.

### Install Composer Dependencies

```bash
composer install
```

### Set Up the Database

1. Create a MySQL database with the name specified in the .env file.

2. Import the SQL schema `mailerlite_api.sql` provided in the repository.

### Serve the Application
```bash
php -S localhost:8000
```

### Access the Application
Open your web browser and navigate to http://localhost:8000.

### Test the Endpoints

1. Test the write endpoint by making a POST request to http://localhost:8000/write_subscriber.php with the required parameters (email, name, last_name, status).

2. Test the retrieve endpoint by making a GET request to http://localhost:8000/get_subscriber.php with the email parameter.

### Access the Vue Page

Navigate to http://localhost:8000/index.html to access the Vue page. Use the form to insert and retrieve subscribers. 
