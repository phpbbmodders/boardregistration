<?php
/**
 * Script to output the absolute path of the directory where the script is placed.
 * 
 * This script can be used to find the root path of a phpBB installation by placing it 
 * in the installation directory and running it.
 *
 * Usage: Run the script from the command line. It will output the absolute path
 * to the directory where the script is located.
 */

// Get the absolute path of the directory where this script is located
$phpbb_root_path = realpath(__DIR__);

// Output the result
echo "phpBB root path: $phpbb_root_path\n";
?>

