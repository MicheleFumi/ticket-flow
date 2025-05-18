#!/bin/bash

echo "Starting Docker setup..."

# Controlla se sei su macOS
if [[ "$OSTYPE" == "darwin"* ]]; then
    echo "Detected macOS."

    # Controlla se Docker Desktop è attivo
    if ! docker info > /dev/null 2>&1; then
        echo "Docker Desktop is not running. Please start Docker Desktop manually."
        exit 1
    else
        echo "Docker is already running."
    fi
else
    echo "Detected Linux (Debian-based)."
    pkill docker
    sleep 10

    if ! pgrep -x "dockerd" > /dev/null; then
        dockerd &
        DOCKER_PID=$!

        echo "Waiting for Docker to start..."
        while ! docker info > /dev/null 2>&1; do
            sleep 1
        done
        echo "Docker is running."
    else
        echo "Docker daemon is already running."
    fi
fi

# Avvia Docker Compose
echo "Starting Docker Compose..."
docker compose down
docker compose up -d

# Avvia npm
echo "Starting npm..."
npm run dev

echo "Script completed successfully."
