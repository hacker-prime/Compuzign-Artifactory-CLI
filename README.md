# Compuzign-Artifactory-CLI
A command-line interface (CLI) tool for managing JFrog Artifactory instances. This tool supports user management, repository operations, and system information retrieval via the JFrog REST API.

---

## Features

### User Management
- **Create a User**: Add a new user with specified credentials and roles.
- **Delete a User**: Remove an existing user from Artifactory.
- **Display Token**: Retrieve and show the stored authentication token.

### System Information
- **System Version**: Fetch and display the Artifactory system version and revision.

### Authentication
- **Login**: Authenticate with Artifactory using a username and password.

### Help Menu
- **Custom Help Menu**: Run a single command to display all available CLI commands and their usage.

---

## Requirements
- PHP 8.0 or higher
- Composer
- JFrog Artifactory instance with API access

---

## Installation

1. **Clone the repository:**
   ```bash
   git clone https://github.com/hacker-prime/Compuzign-Artifactory-CLI.git
   cd Compuzign-Artifactory-CLI

## Install Dependencies

```bash
composer install
```

---

## Set Up Environment Variables

Create a `.env` file in the root directory with the following content:

```plaintext
# Environment Variables

# Artifactory API Token
ARTIFACTORY_API_TOKEN=

# Artifactory Base URL
ARTIFACTORY_BASE_URL=https://<your-instance>.jfrog.io

# Artifactory Credentials
ARTIFACTORY_USERNAME=<your-username>
ARTIFACTORY_PASSWORD=<your-password>
```

---

## Usage

### Running the CLI Tool

You can run the CLI tool using the following command:

```bash
php bin/console <command>
```

### Available Commands

Run the following command to view all available commands:

```bash
php bin/console list
```

#### Example Commands

1. **Login to Artifactory:**

   ```bash
   php bin/console auth:login
   ```

   Prompts for username, password, and base URL.

2. **Display the stored token:**

   ```bash
   php bin/console auth:show-token
   ```

3. **Create a new user:**

   ```bash
   php bin/console user:create <username> <password> <email> [--admin]
   ```

4. **Delete a user:**

   ```bash
   php bin/console user:delete <username>
   ```

5. **Retrieve and display system version:**

   ```bash
   php bin/console system:version
   ```

6. **Display custom help menu:**

   ```bash
   php bin/console help:menu
   ```

---

## Building the PHAR File

To package the CLI tool into a single executable PHAR file:

1. Ensure [Box](https://github.com/box-project/box) is installed via Composer:

   ```bash
   composer require --dev humbug/box
   ```

2. Run the following command to build the PHAR:

   ```bash
   composer build-phar
   ```

This will create a `compuzign-artifactory-cli.phar` file in the project root directory.

---

## Using the PHAR File

Run the PHAR file directly to use the CLI tool:

```bash
php compuzign-artifactory-cli.phar <command>
```

#### Example Commands

1. **Help Menu:**

   ```bash
   php compuzign-artifactory-cli.phar help
   ```

2. **Create a user:**

   ```bash
   php compuzign-artifactory-cli.phar user:create <username> <password> <email> [--admin]
   ```

3. **Delete a user:**

   ```bash
   php compuzign-artifactory-cli.phar user:delete <username>
   ```

4. **System Version:**

   ```bash
   php compuzign-artifactory-cli.phar system:version
   ```

---
