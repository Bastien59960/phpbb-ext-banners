<?php
namespace bastien59960\banners\migrations;

class release_1_0_0 extends \phpbb\db\migration\migration
{
    public function effectively_installed()
    {
        return $this->db_tools->sql_table_exists($this->table_prefix . 'bastien59_banners');
    }

    static public function depends_on()
    {
        return ['\phpbb\db\migration\data\v330\v330'];
    }

    public function update_schema()
    {
        return [
            'add_tables' => [
                $this->table_prefix . 'bastien59_banners' => [
                    'COLUMNS' => [
                        'banner_id'   => ['UINT', null, 'auto_increment'],
                        'filename'    => ['VCHAR:255', ''],
                        'link_url'    => ['TEXT', ''],
                        'title'       => ['VCHAR:255', ''],
                        'enabled'     => ['BOOL', 1],
                        'weight'      => ['UINT', 1],
                        'sort_order'  => ['UINT', 0],
                    ],
                    'PRIMARY_KEY' => 'banner_id',
                    'KEYS' => [
                        'enabled' => ['INDEX', 'enabled'],
                    ],
                ],
            ],
        ];
    }

    public function revert_schema()
    {
        return [
            'drop_tables' => [
                $this->table_prefix . 'bastien59_banners',
            ],
        ];
    }

    public function update_data()
    {
        return [
            ['config.add', ['bastien59_banners_enabled', 1]],
            ['config.add', ['bastien59_banners_path', 'images/bannieres']],

            ['module.add', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_BANNERS_MANAGEMENT']],
            ['module.add', ['acp', 'ACP_BANNERS_MANAGEMENT', [
                'module_basename' => '\bastien59960\banners\acp\main_module',
                'modes' => ['index'],
            ]]],

            ['custom', [[$this, 'import_existing_banners']]],
        ];
    }

    public function revert_data()
    {
        return [
            ['config.remove', ['bastien59_banners_enabled']],
            ['config.remove', ['bastien59_banners_path']],

            ['module.remove', ['acp', 'ACP_BANNERS_MANAGEMENT', [
                'module_basename' => '\bastien59960\banners\acp\main_module',
                'modes' => ['index'],
            ]]],
            ['module.remove', ['acp', 'ACP_CAT_DOT_MODS', 'ACP_BANNERS_MANAGEMENT']],
        ];
    }

    public function import_existing_banners()
    {
        $path = $this->phpbb_root_path . 'images/bannieres/';
        if (!is_dir($path))
        {
            return;
        }

        // Hardcoded links from existing templates
        $links = [
            'forumbanniere1.jpg'  => 'https://forum.debucquoi.com/viewtopic.php?f=155&t=15169',
            'forumbanniere7.jpg'  => 'http://www.graphite-garage.com',
            'forumbanniere24.jpg' => 'https://forum.debucquoi.com/memberlist.php?mode=viewprofile&u=8',
            'forumbanniere30.jpg' => 'https://www.youtube.com/@voyageetnouvellevie',
            'forumbanniere50.jpg' => 'https://www.youtube.com/channel/UCNyJBRPzh0bHFyOAiRNnxwQ/videos',
            'forumbanniere56.jpg' => 'https://www.youtube.com/@voyageetnouvellevie',
            'forumbanniere59.jpg' => 'https://www.facebook.com/michel.soules',
            'forumbanniere60.jpg' => 'https://forum.debucquoi.com/viewtopic.php?t=18609',
            'forumbanniere61.jpg' => 'https://www.instagram.com/roulemapoule.voyage/',
            'forumbanniere62.jpg' => 'https://www.instagram.com/juno.road/',
            'forumbanniere63.jpg' => 'https://www.instagram.com/gasoildream/',
            'forumbanniere64.mp4' => 'https://www.instagram.com/roulemapoule.voyage/',
            'forumbanniere65.mp4' => 'https://www.instagram.com/juno.road/',
            'forumbanniere66.mp4' => 'https://www.instagram.com/juno.road/',
            'forumbanniere67.mp4' => 'https://forum.debucquoi.com/memberlist.php?mode=viewprofile&u=8',
            'forumbanniere68.mp4' => 'https://forum.debucquoi.com/memberlist.php?mode=viewprofile&u=11106',
            'forumbanniere69.mp4' => 'https://www.youtube.com/@voyageetnouvellevie',
            'forumbanniere70.mp4' => 'https://www.youtube.com/@voyageetnouvellevie',
            'forumbanniere71.mp4' => 'https://www.youtube.com/@voyageetnouvellevie',
            'forumbanniere72.mp4' => 'https://forum.debucquoi.com/viewtopic.php?p=224078',
            'forumbanniere73.jpg' => 'https://forum.debucquoi.com/viewtopic.php?p=224078',
            'forumbanniere74.jpg' => 'https://forum.debucquoi.com/viewtopic.php?p=224078',
            'forumbanniere75.mp4' => 'https://www.youtube.com/@zaberic26enroadtrip15/videos',
            'forumbanniere76.jpg' => 'https://www.instagram.com/lacaravanemedicale/',
        ];

        $titles = [
            'forumbanniere1.jpg'  => 'Forum topic',
            'forumbanniere7.jpg'  => 'Graphite Garage',
            'forumbanniere24.jpg' => 'Dimitri',
            'forumbanniere30.jpg' => 'Voyage et nouvelle vie',
            'forumbanniere50.jpg' => 'YouTube channel',
            'forumbanniere56.jpg' => 'Voyage et nouvelle vie',
            'forumbanniere59.jpg' => 'Michel Soules',
            'forumbanniere60.jpg' => "P'tits Gros 2023",
            'forumbanniere61.jpg' => 'muth67 - roulemapoule',
            'forumbanniere62.jpg' => 'Morgan - Juno Road',
            'forumbanniere63.jpg' => 'Ludovic - Gasoil Dream',
            'forumbanniere64.mp4' => 'muth67 - roulemapoule',
            'forumbanniere65.mp4' => 'Morgan - Juno Road',
            'forumbanniere66.mp4' => 'Morgan - Juno Road',
            'forumbanniere67.mp4' => 'Dimitri',
            'forumbanniere68.mp4' => 'Pivot',
            'forumbanniere69.mp4' => 'Voyage et nouvelle vie',
            'forumbanniere70.mp4' => 'Voyage et nouvelle vie',
            'forumbanniere71.mp4' => 'Voyage et nouvelle vie',
            'forumbanniere72.mp4' => "P'tits Gros 2024",
            'forumbanniere73.jpg' => "P'tits Gros 2024",
            'forumbanniere74.jpg' => "P'tits Gros 2024",
            'forumbanniere75.mp4' => 'zaberic26 en road trip',
            'forumbanniere76.jpg' => 'La caravane médicale',
        ];

        $files = scandir($path);
        $order = 0;
        foreach ($files as $file)
        {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'mp4', 'webm', 'webp']))
            {
                continue;
            }

            $order++;
            $sql_ary = [
                'filename'   => $file,
                'link_url'   => isset($links[$file]) ? $links[$file] : '',
                'title'      => isset($titles[$file]) ? $titles[$file] : '',
                'enabled'    => 1,
                'weight'     => 1,
                'sort_order' => $order,
            ];

            $sql = 'INSERT INTO ' . $this->table_prefix . 'bastien59_banners ' . $this->db->sql_build_array('INSERT', $sql_ary);
            $this->db->sql_query($sql);
        }
    }
}
