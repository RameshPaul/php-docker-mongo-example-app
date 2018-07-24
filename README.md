# PHP Docker MongoDB


The goal of this building a production-ready application with **PHP, Composer & MongoDB**.

## Prerequsites

[Docker](https://www.docker.com/products/docker) 

### Technology

- Valid PHP 7.1
- Persist data to MongoDB (in the provided containers).
    - MongoDB connection details:
        - host: `mongodb`
        - port: `27017`
- Use the provided `docker-compose.yml` file in the root of this repository. You are free to add more containers to this if you like.

## Setup

1. Clone this repository.
- Run `docker-compose up -d` to start the development environment.
- Visit `http://localhost` to see the contents of the web container and develop your application.

## Application Features

This is a simple Recipes API which consists below features. 

- List, create, read, update, and delete Recipes
- Search recipes
- Rate recipes

### Recipes Endpoints

| Name   | Method      | URL                    | Protected |
| ---    | ---         | ---                    | ---       |
| List   | `GET`       | `/recipes`             | ✘         |
| Create | `POST`      | `/recipes`             | ✓         |
| Get    | `GET`       | `/recipes/{id}`        | ✘         |
| Update | `PUT/PATCH` | `/recipes/{id}`        | ✓         |
| Delete | `DELETE`    | `/recipes/{id}`        | ✓         |
| Rate   | `POST`      | `/recipes/{id}/rating` | ✘         |

### Schema

- **Recipe**
    - Unique ID
    - Name
    - Prep time
    - Difficulty (1-3)
    - Vegetarian (boolean)

