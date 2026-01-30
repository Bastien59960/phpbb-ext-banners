<?php
namespace bastien59960\banners\acp;

class main_info
{
    public function __construct()
    {
        global $phpbb_container;

        if (isset($phpbb_container))
        {
            $user = $phpbb_container->get('user');
            $user->add_lang_ext('bastien59960/banners', 'acp/info_acp_banners');
        }
    }

    public function module()
    {
        global $user;
        if (!isset($user->lang['ACP_BANNERS_MANAGEMENT']))
        {
            $user->add_lang_ext('bastien59960/banners', 'acp/info_acp_banners');
        }

        return [
            'filename'  => '\bastien59960\banners\acp\main_module',
            'title'     => 'ACP_BANNERS_MANAGEMENT',
            'modes'     => [
                'index' => [
                    'title' => 'ACP_BANNERS_MANAGEMENT',
                    'auth'  => 'ext_bastien59960/banners && acl_a_board',
                    'cat'   => ['ACP_BANNERS_MANAGEMENT'],
                ],
            ],
        ];
    }
}
