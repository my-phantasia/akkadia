# Start the development environment
up:
    docker compose up -d

# Stop the development environment
stop:
    docker compose stop

# Completely tear down the container
destroy:
    docker compose down

# Run database migrations
migrate:
    docker compose exec app php artisan migrate

# Run any artisan command (e.g., 'just artisan make:controller MyController')
artisan *args:
    docker compose exec app php artisan {{args}}

# Install/Update backend dependencies
composer *args:
    docker compose exec app composer {{args}}

# Install frontend dependencies (if needed)
install:
    docker compose exec app npm install

# Start the Vite development server
dev:
    docker compose exec app npm run dev

# Build assets for production
build:
    docker compose exec app npm run build

# Open a shell inside the PHP container
shell:
    docker compose exec -it app bash
