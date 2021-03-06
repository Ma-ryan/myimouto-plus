<?php $url = Rails::application()->router()->url_helpers() ?>
jQuery(document).ready(function($) {
  $('input.ac-user-name').autocomplete({
    source: '<?= $url->acUserNamePath() ?>',
    minLength: 2
  });
  $('input.ac-tag-name').autocomplete({
    source: '<?= $url->acTagNamePath() ?>',
    minLength: 2
  });
  if ($('#edit-form').length && $('textarea.ac-tags').length) {
    new TagCompletionBox($('textarea.ac-tags')[0]);
    if (TagCompletion) {
      TagCompletion.observe_tag_changes_on_submit($('#edit-form')[0], $('input.ac-tags')[0], null);
    };
  };
});
