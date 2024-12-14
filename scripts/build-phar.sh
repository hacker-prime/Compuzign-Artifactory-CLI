#!/bin/bash

# Ensure Box is installed
if ! command -v box &> /dev/null
then
    echo "Box could not be found. Please install Box to proceed."
    exit
fi

# Generate the PHAR file using Box
box compile
