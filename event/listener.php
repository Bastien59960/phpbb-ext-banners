<?php
namespace bastien59960\banners\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
    protected $db;
    protected $config;
    protected $template;
    protected $table_prefix;
    protected $root_path;

    public function __construct($db, $config, $template, $table_prefix, $root_path)
    {
        $this->db = $db;
        $this->config = $config;
        $this->template = $template;
        $this->table_prefix = $table_prefix;
        $this->root_path = $root_path;
    }

    static public function getSubscribedEvents()
    {
        return [
            'core.page_header_after' => 'on_page_header_after',
        ];
    }

    public function on_page_header_after($event)
    {
        if (empty($this->config['bastien59_banners_enabled']))
        {
            return;
        }

        $banner_path = $this->config['bastien59_banners_path'];
        $table = $this->table_prefix . 'bastien59_banners';

        // Weighted random selection: repeat each banner_id by its weight,
        // then pick one at random in PHP (compatible with all DB backends).
        $sql = "SELECT banner_id, filename, link_url, weight FROM {$table} WHERE enabled = 1";
        $result = $this->db->sql_query($sql);

        $pool = [];
        while ($row = $this->db->sql_fetchrow($result))
        {
            $w = max(1, (int) $row['weight']);
            for ($i = 0; $i < $w; $i++)
            {
                $pool[] = $row;
            }
        }
        $this->db->sql_freeresult($result);

        if (empty($pool))
        {
            return;
        }

        $banner = $pool[array_rand($pool)];
        $ext = strtolower(pathinfo($banner['filename'], PATHINFO_EXTENSION));

        // Échapper les données DB — autoescape Twig est OFF dans phpBB 3.3
        $this->template->assign_vars([
            'BANNER_ENABLED' => true,
            'BANNER_FILE'    => htmlspecialchars(generate_board_url() . '/' . $banner_path . '/' . $banner['filename'], ENT_QUOTES, 'UTF-8'),
            'BANNER_LINK'    => htmlspecialchars($banner['link_url'], ENT_QUOTES, 'UTF-8'),
            'BANNER_EXT'     => htmlspecialchars($ext, ENT_QUOTES, 'UTF-8'),
        ]);
    }
}
