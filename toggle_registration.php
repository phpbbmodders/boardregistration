#!/usr/bin/env php
<?php
/**
 * Script to toggle user registration on a phpBB 3.3 board.
 * 
 * copyright phpBBModder https://phpbbmodders.com
 *
 * This script logs in as a specific administrative user (using user ID)
 * and uses phpBB's configuration system to enable or disable user registration.
 * It is designed to be run from a cron job and includes a token-based security
 * mechanism to prevent unauthorized execution.
 *
 * User-Editable Configuration:
 * - $phpbb_root_path: Path to the phpBB installation. Use the path.php to find the system path to your forum, and set it appropriately.
 * - $phpEx: PHP file extension. If you don't know what this is, leave as is.
 * - $admin_user_id: The ID of the administrative user for authentication. Change to your user.
 * - $token: A security token to authenticate the script run. Change this.
 */

// User-Editable Configuration
$phpbb_root_path = '/media/william/NewData/homelab/docker-lamp/www/community/'; // Path to phpBB installation
$phpEx = substr(strrchr(__FILE__, '.'), 1); // PHP file extension

$admin_user_id = 2; // User ID of the administrative user
$token = 'securetoken123'; // Replace with a secure token

// End of User-Editable Configuration

// Check if token and action are passed as arguments
if ($argc < 3 || $argv[1] !== $token || !in_array($argv[2], ['enable', 'disable'])) {
    die("Invalid or missing token/action. Usage: script.php <token> <enable|disable>\n");
}

// Determine the action based on the argument
$action = $argv[2];
$disable = ($action === 'disable');

// Include necessary phpBB files
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : $phpbb_root_path;
require($phpbb_root_path . 'common.' . $phpEx);
require($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

// Start session management and initialize
$user->session_begin();
$auth->acl($user->data);
$user->setup();

/**
 * Logs in as a specific user using the user ID.
 *
 * @param int $user_id The ID of the user to log in as.
 * @return bool True if login is successful, false otherwise.
 */
function login_as_user_id($user_id) {
    global $user, $auth, $db;

    // Fetch user information
    $sql = 'SELECT * FROM ' . USERS_TABLE . ' WHERE user_id = ' . (int)$user_id;
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $db->sql_freeresult($result);

    // If user exists, log in
    if ($row) {
        // Set user's data and create a session
        $auth->acl($row);
        $user->data = $row;
        $user->ip = '127.0.0.1'; // Use a default IP address
        $user->session_create($row['user_id'], false);
        if ($user->data['user_id'] == $user_id) {
            echo "Logged in as user: " . $user->data['username'] . "\n";
            return true;
        }
    }

    echo "Failed to log in as user with ID: $user_id\n";
    return false;
}

/**
 * Toggles registration status on the board.
 *
 * @param bool $disable True to disable registration, false to enable.
 */
function toggle_registration($disable) {
    global $config, $cache;

    // Set the require_activation configuration based on disable flag
    $value = $disable ? USER_ACTIVATION_DISABLE : USER_ACTIVATION_NONE;

    // Update the configuration value
    set_config('require_activation', $value, true);

    // Purge the cache to apply changes immediately
    $cache->purge();

    echo "Registration " . ($disable ? "disabled" : "enabled") . " successfully.\n";
}

// Log in as the specified admin user using user ID
if (login_as_user_id($admin_user_id)) {
    // Toggle registration status based on the action argument
    toggle_registration($disable);

    // Logout user after operation to clean up session
    $user->session_kill();
    $user->session_begin();
} else {
    echo "Unable to log in as the specified user. Ensure the user ID is correct.\n";
}
?>

