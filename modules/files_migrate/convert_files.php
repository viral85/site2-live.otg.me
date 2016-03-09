<?php

/**
* Populate file object
* http://api.drupal.org/api/drupal/includes!file.inc/group/file/7
*/

function convert_files_migrate_pre_process(&$object) {
  $result = db_query("SELECT * FROM {webfm_file} WHERE fid = :fid", array(':nid' => $object->nid));
  $record = $result->fetchField();
   
  $object->article_custom_field = $record;
}

/*File Object



/**
 * Submit handler to write a managed file.
 *
 * The key functions used here are:
 * - file_save_data(), which takes a buffer and saves it to a named file and
 *   also creates a tracking record in the database and returns a file object.
 *   In this function we use FILE_EXISTS_RENAME (the default) as the argument,
 *   which means that if there's an existing file, create a new non-colliding
 *   filename and use it.
 * - file_create_url(), which converts a URI in the form public://junk.txt or
 *   private://something/test.txt into a URL like
 *   http://example.com/sites/default/files/junk.txt.
 */
function convert_files_managed_write_submit($form, &$form_state) {
  $data = $form_state['values']['write_contents'];
  $uri = !empty($form_state['values']['destination']) ? $form_state['values']['destination'] : NULL;

  // Managed operations work with a file object.
  $file_object = file_save_data($data, $uri, FILE_EXISTS_RENAME);
  if (!empty($file_object)) {
    $url = file_create_url($file_object->uri);
    $_SESSION['file_example_default_file'] = $file_object->uri;
    drupal_set_message(t('Saved managed file: %file to destination %destination (accessible via !url, actual uri=<span id="uri">@uri</span>)', array('%file' => print_r($file_object, TRUE), '%destination' => $uri, '@uri' => $file_object->uri, '!url' => l(t('this URL'), $url))));
  }
  else {
    drupal_set_message(t('Failed to save the managed file'), 'error');
  }
}