<?php
namespace bastien59960\banners\acp;

class main_module
{
    public $u_action;
    public $tpl_name;
    public $page_title;

    public function main($id, $mode)
    {
        global $phpbb_container, $user;

        $user->add_lang_ext('bastien59960/banners', 'acp/info_acp_banners');

        $this->tpl_name = 'acp_banners_body';
        $this->page_title = $user->lang('ACP_BANNERS_MANAGEMENT');

        $controller = $phpbb_container->get('bastien59960.banners.acp.controller');
        $controller->display($this->u_action);
    }
}
