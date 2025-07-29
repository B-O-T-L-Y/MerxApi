# Product API

A simple RESTful API for managing products built with **PHP 8.4**, **FastRoute**, **Doctrine DBAL**, and **PostgreSQL**.

## Features
- CRUD operations for products
- Filtering by category and price
- Error handling middleware
- Dockerized setup with PostgreSQL

## Requirements
- Docker & Docker Compose

## Setup

### Build and start containers
```bash
  make build
```

### Up containers
```bash
  make up
```

### Run database migration
```bash
  make migrate
```

### Seed initial data
```bash
  make seed
```

### Stop containers
```bash
  make down
```

### Enter PHP container
```bash
  make back
```

### Install dependencies of a project
```bash
  make install
```

**OR USE COMMANDS in Makefile**

## Access the API
#### The API is available at:
[http://localhost:8000](http://localhost:8000)

## API Endpoints
**POST** `/products` — Create a new product

**GET** `/products/{id}` — Get product by ID

**PATCH** `/products/{id}` — Update product by ID

**DELETE** `/products/{id}` — Delete product by ID

**GET** `/products` — List products (optional filters: category, price)

## API Testing

A Postman collection with all available endpoints is included in the repository:

[Postman Collection](./postman/collection.json) => `postman/collection.json`

You can import this file into Postman to quickly test and explore the API.
