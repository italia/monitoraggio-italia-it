<?php
/**
 * @file
 * Contains functions for drush integration.
 */

/**
 * Implementation of hook_drush_command()
 */
function italiagov_drush_command() {
  $items = array();

  $items['italiagov-sub-theme'] = array(
    'description' => 'Instructions to create an ItaliaGov sub-theme',
    'aliases' => array('igst'),
    'arguments' => array(
      'name' => 'The human readable name of sub-theme.',
      'machine_name' => 'A machine-readable name of theme, optional only  [a-z, 0-9] ',
    ),
    'options' => array(
      'description' => 'Your sub-theme description.',
      'machine-name' => '[a-z, 0-9] A machine-readable name for your theme.'
    ),
    'examples' => array(
      'drush igst "My sub-theme"' => 'Create a sub-theme with the default options.',
      'drush igst "My sub-theme" "my_sub_theme"  --description="My sub-theme description"' => 'Create a sub-theme with additional options.',
    ),
  );

  return $items;
}

/**
 * Create an ItaliaGov sub-theme
 */
function drush_italiagov_sub_theme($name = NULL, $machine_name = NULL, $description = NULL) {
  if (empty($name)) {
    drush_set_error(
      dt("To create an ItaliaGov sub-theme, please provide a name for it.\nUSAGE:\tdrush igst [name] [machine_name !OPTIONAL] [description !OPTIONAL]\n")
    );
    return;
  }
  // Filter everything but letters, numbers, underscores, and hyphens
  $machine_name = !empty($machine_name)
    ? preg_replace('/[^a-z0-9_-]+/', '', strtolower($machine_name))
    : preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens
  $machine_name = str_replace('-', '_', $machine_name);

  // Manage the description of the theme
  $description = (!empty($description)) ? $description : 'Sub theme of ItaliaGov base theme';

  $base_theme_path = drush_get_context('DRUSH_DRUPAL_ROOT') . '/' . drupal_get_path('theme', 'italiagov');
  $subtheme_path = explode('/', $base_theme_path);
  array_pop($subtheme_path);
  $subtheme_path = implode('/', $subtheme_path) . '/' . $machine_name;

  // Make a copy of the subtheme
  $copied = drush_copy_dir("$base_theme_path/STARTERKIT/", $subtheme_path);
  if (empty($copied)) {
    return;
  }

  // Copy of IWT builder folder
  drush_op('xcopy', "$base_theme_path/ita-web-toolkit-build", "$subtheme_path/ita-web-toolkit-build");

  // Rename files and fill in the theme machine name
  drush_op('rename', "$subtheme_path/STARTERKIT.info.yml.txt", "$subtheme_path/$machine_name.info.yml");
  drush_op('rename', "$subtheme_path/STARTERKIT.libraries.yml", "$subtheme_path/$machine_name.libraries.yml");
  drush_op('rename', "$subtheme_path/css/STARTERKIT.css", "$subtheme_path/css/$machine_name.css");
  drush_op('rename', "$subtheme_path/js/STARTERKIT.js", "$subtheme_path/js/$machine_name.js");

  // Replacement of stub starterkit instances in required files
  drush_op('italiagov_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'STARTERKIT_NAME', $name);
  drush_op('italiagov_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'STARTERKIT_DESCRIPTION', $description);
  drush_op('italiagov_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'STARTERKIT', $machine_name);
  drush_op('italiagov_file_str_replace', "$subtheme_path/$machine_name.libraries.yml", 'STARTERKIT', $machine_name);
  drush_op('italiagov_file_str_replace', "$subtheme_path/gulpfile.js", 'STARTERKIT', $machine_name);
  drush_op('italiagov_file_str_replace', "$subtheme_path/bower.json", 'STARTERKIT', $name);
  drush_op('italiagov_file_str_replace', "$subtheme_path/package.json", 'STARTERKIT', $machine_name);

  // Sub-theme created: notify user
  $message = "\n!name sub-theme was succesfully created in !path.";
  $message .= "\nto install it go to \"Appearance\" settings section (admin/appearance).\n";
  drush_print(
    dt($message,
      array(
        '!name' => $name,
        '!path' => $subtheme_path
      )
    )
  );
}

/**
 * Internal helper: Replace strings in a file
 */
function italiagov_file_str_replace($file_path, $find, $replace) {
  $file_contents = file_get_contents($file_path);
  $file_contents = str_replace($find, $replace, $file_contents);
  file_put_contents($file_path, $file_contents);
}

/**
 * Implements hook_drush_help()
 */
function italiagov_drush_help($section) {
  switch ($section) {
    case 'drush:italiagov-sub-theme':
      return dt('Instructions to create an ItaliaGov sub-theme.');
  }
}

/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string $source Source path
 * @param       string $dest Destination path
 * @param       int $permissions New folder creation permissions
 * @return      bool     Returns true on success, false on failure
 */
function xcopy($source, $dest, $permissions = 0755) {
  // Check for symlinks
  if (is_link($source)) {
    return symlink(readlink($source), $dest);
  }

  // Simple copy for a file
  if (is_file($source)) {
    return copy($source, $dest);
  }

  // Make destination directory
  if (!is_dir($dest)) {
    mkdir($dest, $permissions);
  }

  // Loop through the folder
  $dir = dir($source);
  while (FALSE !== $entry = $dir->read()) {
    // Skip pointers
    if ($entry == '.' || $entry == '..') {
      continue;
    }

    // Deep copy directories
    xcopy("$source/$entry", "$dest/$entry", $permissions);
  }

  // Clean up
  $dir->close();
  return TRUE;
}