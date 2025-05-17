#!/bin/bash

echo "Starting Docker daemon in the foreground..."
pkill docker
sleep 10

if ! pgrep -x "dockerd" > /dev/null; then
    dockerd &
    DOCKER_PID=$!

    # Attendi che Docker sia completamente avviato
    echo "Waiting for Docker to start..."
    while ! docker info > /dev/null 2>&1; do
        sleep 1
    done
    echo "Docker is running."
else
    echo "Docker daemon is already running."
fi

# Avvia Docker Compose in modalità non detached
echo "Starting Docker Compose..."
docker compose down
docker compose up -d

echo "Starting npm..."
npm run dev

echo "Script completed successfully."