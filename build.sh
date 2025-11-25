#!/bin/bash
# Render build script for Winter E-com

# Install PHP dependencies
composer install --no-dev

# Create necessary directories
mkdir -p uploads
mkdir -p session

# Set proper permissions
chmod -R 755 uploads
chmod -R 755 session
chmod -R 644 css
chmod -R 644 js
