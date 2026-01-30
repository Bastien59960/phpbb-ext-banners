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
    'ACP_BANNERS_TITLE'         => 'Gestion des bannières',
    'ACP_BANNERS_MANAGEMENT'    => 'Gestion des bannières',
    'ACP_BANNERS_ENABLED'       => 'Activer les bannières',
    'ACP_BANNERS_PATH'          => 'Chemin des images',
    'ACP_BANNERS_PATH_EXPLAIN'  => 'Chemin relatif à la racine phpBB, ex : <samp>images/bannieres</samp>',
    'ACP_BANNERS_SETTINGS_SAVED'=> 'Paramètres des bannières enregistrés.',
    'ACP_BANNERS_SCAN'          => 'Scanner le dossier',
    'ACP_BANNERS_SCAN_DONE'     => '%d nouvelle(s) bannière(s) importée(s).',
    'ACP_BANNERS_SCAN_NONE'     => 'Aucun nouveau fichier trouvé.',
    'ACP_BANNERS_FILE'          => 'Fichier',
    'ACP_BANNERS_LINK'          => 'URL du lien',
    'ACP_BANNERS_TITLE_COL'     => 'Titre',
    'ACP_BANNERS_ACTIVE'        => 'Actif',
    'ACP_BANNERS_PREVIEW'       => 'Aperçu',
    'ACP_BANNERS_DELETE'        => 'Supprimer',
    'ACP_BANNERS_DELETE_CONFIRM'=> 'Êtes-vous sûr de vouloir supprimer cette bannière ?',
    'ACP_BANNERS_DELETED'       => 'Bannière supprimée.',
    'ACP_BANNERS_UPDATED'       => 'Bannière mise à jour.',
    'ACP_BANNERS_NO_BANNERS'    => 'Aucune bannière trouvée.',
    'ACP_BANNERS_SAVE'          => 'Enregistrer',
]);
