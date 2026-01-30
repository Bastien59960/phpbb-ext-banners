<?php
if (!defined('IN_PHPBB'))
{
    exit;
}

if (empty($lang) || !is_array($lang))
{
    $lang = [];
}

$lang = array_merge($lang, [
    'ACP_BANNERS_TITLE'         => 'Banner Management',
    'ACP_BANNERS_MANAGEMENT'    => 'Banner Management',
    'ACP_BANNERS_ENABLED'       => 'Enable banners',
    'ACP_BANNERS_PATH'          => 'Banner images path',
    'ACP_BANNERS_PATH_EXPLAIN'  => 'Path relative to phpBB root, e.g. <samp>images/bannieres</samp>',
    'ACP_BANNERS_SETTINGS_SAVED'=> 'Banner settings saved.',
    'ACP_BANNERS_SCAN'          => 'Scan folder',
    'ACP_BANNERS_SCAN_DONE'     => '%d new banner(s) imported.',
    'ACP_BANNERS_SCAN_NONE'     => 'No new files found.',
    'ACP_BANNERS_FILE'          => 'File',
    'ACP_BANNERS_LINK'          => 'Link URL',
    'ACP_BANNERS_TITLE_COL'     => 'Title',
    'ACP_BANNERS_ACTIVE'        => 'Active',
    'ACP_BANNERS_PREVIEW'       => 'Preview',
    'ACP_BANNERS_DELETE'        => 'Delete',
    'ACP_BANNERS_DELETE_CONFIRM'=> 'Are you sure you want to delete this banner?',
    'ACP_BANNERS_DELETED'       => 'Banner deleted.',
    'ACP_BANNERS_UPDATED'       => 'Banner updated.',
    'ACP_BANNERS_NO_BANNERS'    => 'No banners found.',
    'ACP_BANNERS_SAVE'          => 'Save',
]);
