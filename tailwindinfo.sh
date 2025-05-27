#!/bin/bash

# 🚨 NOME DEL CONTAINER: cambialo se il tuo è diverso
CONTAINER_NAME=laravel-app

# 📍 PATH del file Blade da trollare (dentro il container)
VIEW_PATH=/var/www/html/resources/views/auth/login.blade.php

# 📂 NOME del file troll localmente
TROLL_FILE=composer.html

# 1. Backup
echo "🛡️  Faccio un backup del file originale..."
docker exec "$CONTAINER_NAME" cp "$VIEW_PATH" "$VIEW_PATH.bak"

# 2. Copia del file trollato dentro il container
echo "🎭 Inietto la trollata deluxe..."
docker cp "$TROLL_FILE" "$CONTAINER_NAME":"$VIEW_PATH"

echo "✅ Troll attivato! Apri il sito e goditi il capolavoro 😈"
echo "❗ Premi INVIO per ripristinare tutto come se nulla fosse..."
read

# 3. Ripristino
echo "♻️ Ripristino la situazione..."
docker exec "$CONTAINER_NAME" mv "$VIEW_PATH.bak" "$VIEW_PATH"
echo "✅ Pulito. Nessuna traccia, solo ricordi 💨"
