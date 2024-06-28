```markdown
# Symfony RESTful API Project

This project is a RESTful API built with Symfony, featuring product management endpoints, JWT authentication, and Docker configuration.

## Table of Contents

1. [Setup](#setup)
2. [Architecture Overview](#architecture-overview)
3. [Development](#development)
4. [Migrations](#migrate)
5. [Generate JWT keys](...)
6. [Database Seeding](#database-seeding)

## Setup

### Prerequisites

- Docker and Docker Compose
- Composer (for local development)

### Installation

1. Clone the repository:
   ```
git clone https://github.com/hirwaf/symfony-assessment-project.git
cd symfony-assessment-project
   ```
2. Copy the `.env.test` file to `.env` and adjust
   ```
cp .env.test .env
   ```
3. Build and start the Docker containers:
   ```
docker-compose up --build
   ```
4. Run database migrations ( Development ):
   ```
docker-compose exec app php bin/console doctrine:migrations:migrate
   ```
5. Generate JWT keys:
   ```
docker-compose exec app php bin/console lexik:jwt:generate-keypair
   ```
6. Seed the database with an initial admin user:
   ```
docker-compose exec app php bin/console doctrine:fixtures:load
   ```
The application should now be accessible at `http://localhost:8000`.

## Architecture Overview

This application follows a typical Symfony project structure:

- `src/Controller/`: Contains the API endpoints logic
- `src/Entity/`: Defines the database entities (Product, User)
- `src/Repository/`: Handles database queries
- `src/EventListener/`: Custom exception handling
- `config/`: Application and package configuration
- `tests/`: PHPUnit tests

Key components:
- Symfony 5.4 framework
- Doctrine ORM for database interactions
- LexikJWTAuthenticationBundle for JWT authentication
- Symfony Validator for request validation

## API Documentation

### Authentication

To authenticate, send a POST request to `/api/login` with the following body:

```json
{
  "username": "admin",
  "password": "admin_password"
}
```

Use the returned JWT token in the Authorization header for subsequent requests:

```
Accept: application/json
Authorization: Bearer <token>
```

### Endpoints

#### Get all products

- **URL**: `/api/products`
- **Method**: GET
- **Auth required**: Yes

#### Create a product

- **URL**: `/api/products`
- **Method**: POST
- **Auth required**: Yes
- **Data constraints**:
  ```json
  {
    "name": "[1 to 255 chars]",
    "description": "[non-empty]",
    "price": "[positive number]"
  }
  ```

#### Get a single product

- **URL**: `/api/products/{id}`
- **Method**: GET
- **Auth required**: Yes

#### Update a product

- **URL**: `/api/products/{id}`
- **Method**: PUT
- **Auth required**: Yes
- **Data constraints**: Same as for creating a product

#### Delete a product

- **URL**: `/api/products/{id}`
- **Method**: DELETE
- **Auth required**: Yes

## Development

To run commands inside the Docker container, use:

```bash
docker-compose exec app <command>
```
