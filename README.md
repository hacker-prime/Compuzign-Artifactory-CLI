# Compuzign-Artifactory-CLI
A command-line interface (CLI) tool for managing JFrog Artifactory instances, supporting user management, repository operations, and system information via the JFrog REST API.

## Building the PHAR File

To build the PHAR file for the Compuzign-Artifactory-CLI, follow these steps:

1. Ensure you have [Box](https://github.com/box-project/box) installed via Composer. If not, you can install it by running:

    ```bash
    composer require --dev humbug/box
    ```

2. Run the following command to generate the PHAR file:

    ```bash
    composer build-phar
    ```

This will create a `compuzign-artifactory-cli.phar` file in the project root directory.

## Using the PHAR File

Once you have built the PHAR file, you can use it to run the CLI commands. Here are some examples of common commands:

1. Display help information:

    ```bash
    php compuzign-artifactory-cli.phar help
    ```

2. List all users:

    ```bash
    php compuzign-artifactory-cli.phar user:list
    ```

3. Create a new repository:

    ```bash
    php compuzign-artifactory-cli.phar repo:create <repository-name>
    ```

4. Get system information:

    ```bash
    php compuzign-artifactory-cli.phar system:info
    ```

For more information on available commands, you can use the `help` command as shown above.
