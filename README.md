# SNS IMS

## Introduction

This repository contains the code for the SNS(Safety Necessary Group) IMS project. The project is a web application that allows users to manage
their inventory. The application is built using the Laravel web framework.

## Installation

To install the project, follow the steps below:

1. Clone the repository to your local machine.

 ```bash 
    git clone https://github.com/CaMiMtoto/GEA-IMS.git
```

2. Change into the project directory.

```bash
    cd GEA-IMS
```

3. Install the project dependencies.

```bash
    composer install
```

4. Create a new `.env` file by copying the `.env.example` file.

```bash
    cp .env.example .env
```

5. Generate a new application key.

```bash
    php artisan key:generate
```

6. Create a new database and update the `.env` file with the database credentials.
7. Run the database migrations.

```bash
    php artisan migrate
```

8. Start the development server.

```bash
    php artisan serve
```

9. Visit the application in your browser at `http://localhost:8000`.
