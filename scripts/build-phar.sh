#!/bin/bash

# Ensure Box is installed
if ! [ -x "vendor/bin/box" ]; then
    echo "Box could not be found. Please install Box to proceed."
    exit
fi

# Generate the PHAR file using Box
vendor/bin/box compile
