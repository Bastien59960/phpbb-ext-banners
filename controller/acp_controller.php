<?php
namespace bastien59960\banners\controller;

class acp_controller
{
    protected $db;
    protected $template;
    protected $request;
    protected $user;
    protected $config;
    protected $table_prefix;
    protected $root_path;

    public function __construct($db, $template, $request, $user, $config, $table_prefix, $root_path)
    {
        $this->db = $db;
        $this->template = $template;
        $this->request = $request;
        $this->user = $user;
        $this->config = $config;
        $this->table_prefix = $table_prefix;
        $this->root_path = $root_path;
    }

    public function display($u_action)
    {
        $this->user->add_lang_ext('bastien59960/banners', 'acp/info_acp_banners');
        $table = $this->table_prefix . 'bastien59_banners';

        // Handle settings save
        if ($this->request->is_set_post('submit_settings'))
        {
            if (!check_form_key('bastien59_banners'))
            {
                trigger_error('FORM_INVALID');
            }

            $this->config->set('bastien59_banners_enabled', $this->request->variable('banners_enabled', 1));
            $this->config->set('bastien59_banners_path', $this->request->variable('banners_path', 'images/bannieres'));

            trigger_error($this->user->lang('ACP_BANNERS_SETTINGS_SAVED') . adm_back_link($u_action));
        }

        // Handle scan
        if ($this->request->is_set_post('submit_scan'))
        {
            if (!check_form_key('bastien59_banners'))
            {
                trigger_error('FORM_INVALID');
            }

            $count = $this->scan_folder();
            if ($count > 0)
            {
                trigger_error(sprintf($this->user->lang('ACP_BANNERS_SCAN_DONE'), $count) . adm_back_link($u_action));
            }
            else
            {
                trigger_error($this->user->lang('ACP_BANNERS_SCAN_NONE') . adm_back_link($u_action));
            }
        }

        // Handle delete
        $delete_id = $this->request->variable('delete', 0);
        if ($delete_id > 0)
        {
            if (confirm_box(true))
            {
                $sql = "DELETE FROM {$table} WHERE banner_id = " . (int) $delete_id;
                $this->db->sql_query($sql);
                trigger_error($this->user->lang('ACP_BANNERS_DELETED') . adm_back_link($u_action));
            }
            else
            {
                confirm_box(false, $this->user->lang('ACP_BANNERS_DELETE_CONFIRM'), build_hidden_fields([
                    'delete' => $delete_id,
                ]));
            }
        }

        // Handle inline update (single banner)
        $update_id = $this->request->variable('update_id', 0);
        if ($update_id > 0 && $this->request->is_set_post('submit_update'))
        {
            if (!check_form_key('bastien59_banners'))
            {
                trigger_error('FORM_INVALID');
            }

            $sql_ary = [
                'link_url' => $this->request->variable('link_url', ''),
                'title'    => $this->request->variable('banner_title', '', true),
                'enabled'  => $this->request->variable('enabled', 0),
                'weight'   => max(1, $this->request->variable('weight', 1)),
            ];

            $sql = "UPDATE {$table} SET " . $this->db->sql_build_array('UPDATE', $sql_ary) . ' WHERE banner_id = ' . (int) $update_id;
            $this->db->sql_query($sql);

            trigger_error($this->user->lang('ACP_BANNERS_UPDATED') . adm_back_link($u_action));
        }

        add_form_key('bastien59_banners');

        // List banners
        $banner_path = $this->config['bastien59_banners_path'];
        $sql = "SELECT * FROM {$table} ORDER BY sort_order ASC, banner_id ASC";
        $result = $this->db->sql_query($sql);

        while ($row = $this->db->sql_fetchrow($result))
        {
            $ext = strtolower(pathinfo($row['filename'], PATHINFO_EXTENSION));
            $is_video = in_array($ext, ['mp4', 'webm']);

            $this->template->assign_block_vars('banners', [
                'BANNER_ID'  => $row['banner_id'],
                'FILENAME'   => $row['filename'],
                'LINK_URL'   => $row['link_url'],
                'TITLE'      => $row['title'],
                'ENABLED'    => (int) $row['enabled'],
                'WEIGHT'     => (int) $row['weight'],
                'IS_VIDEO'   => $is_video,
                'PREVIEW_URL'=> '../' . $banner_path . '/' . $row['filename'],
                'U_DELETE'   => $u_action . '&amp;delete=' . $row['banner_id'],
            ]);
        }
        $this->db->sql_freeresult($result);

        $this->template->assign_vars([
            'U_ACTION'         => $u_action,
            'BANNERS_ENABLED'  => (int) $this->config['bastien59_banners_enabled'],
            'BANNERS_PATH'     => $this->config['bastien59_banners_path'],
        ]);
    }

    protected function scan_folder()
    {
        $table = $this->table_prefix . 'bastien59_banners';
        $banner_path = $this->config['bastien59_banners_path'];
        $full_path = $this->root_path . $banner_path . '/';

        if (!is_dir($full_path))
        {
            return 0;
        }

        // Get existing filenames
        $existing = [];
        $sql = "SELECT filename FROM {$table}";
        $result = $this->db->sql_query($sql);
        while ($row = $this->db->sql_fetchrow($result))
        {
            $existing[$row['filename']] = true;
        }
        $this->db->sql_freeresult($result);

        // Get max sort_order
        $sql = "SELECT MAX(sort_order) as max_order FROM {$table}";
        $result = $this->db->sql_query($sql);
        $order = (int) $this->db->sql_fetchfield('max_order');
        $this->db->sql_freeresult($result);

        $count = 0;
        $files = scandir($full_path);
        foreach ($files as $file)
        {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'webp']))
            {
                continue;
            }
            if (isset($existing[$file]))
            {
                continue;
            }

            $order++;
            $sql_ary = [
                'filename'   => $file,
                'link_url'   => '',
                'title'      => '',
                'enabled'    => 1,
                'weight'     => 1,
                'sort_order' => $order,
            ];
            $sql = 'INSERT INTO ' . $table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary);
            $this->db->sql_query($sql);
            $count++;
        }

        return $count;
    }
}
