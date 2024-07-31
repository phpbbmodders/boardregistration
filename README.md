# phpBB Utility Scripts

This repository contains utility scripts for toggling a phpBB board registrtion on and off via system cron.

## Scripts

### 1. `get_phpbb_root_path.php`

#### Description

Outputs the absolute path of the directory where the script is placed. Useful for determining the root path of a phpBB installation.

#### Usage

1. **Place the Script:**
   - Place `get_phpbb_root_path.php` in the root directory of your phpBB installation.

2. **Run the Script:**
   - Execute the script from the command line using:
     ```sh
     php get_phpbb_root_path.php
     ```

3. **Output:**
   - The script will output the absolute path to the directory where it is located. For example:
     ```
     phpBB root path: /var/www/html/community
     ```

#### Example

To find the phpBB root path, navigate to the phpBB installation directory and run:
```sh
php get_phpbb_root_path.php
```

### 2. `toggle_registration.php`

#### Description

Toggles user registration on a phpBB 3.3 board. This script logs in as a specific administrative user and uses phpBB's configuration system to enable or disable user registration.

#### User-Editable Configuration

- **$phpbb_root_path:** Path to the phpBB installation.
- **$phpEx:** PHP file extension (usually `.php`).
- **$admin_user_id:** ID of the administrative user for authentication.
- **$token:** Security token to authenticate the script run.

#### Usage

1. **Set Configuration Variables:**
   - Edit the script to set `$phpbb_root_path`, `$admin_user_id`, and `$token` values.

2. **Run the Script:**
   - Execute the script from the command line with the token and action (`enable` or `disable`) as arguments:
     ```sh
     php toggle_registration.php securetoken123 enable
     ```

   - To disable registration:
     ```sh
     php toggle_registration.php securetoken123 disable
     ```

3. **Output:**
   - The script will output whether registration was enabled or disabled based on the action argument.

#### Example

To disable user registration, use:
```sh
php toggle_registration.php securetoken123 disable
```

To enable user registration, use:
```sh
php toggle_registration.php securetoken123 enable
```

### Contents

- **`get_phpbb_root_path.php`:** Description, usage, and example for finding the phpBB root path.
- **`toggle_registration.php`:** Description, user-editable configuration, usage, and examples for toggling user registration on the phpBB board.
