<?php

$this->require_extension('jquery');
$this->require_extension('timepicker');

require_once dirname(__FILE__).'/events/db.php';
require_once dirname(__FILE__).'/events/location_admin_write_missionpost.php';
require_once dirname(__FILE__).'/events/location_admin_manage_posts_edit.php';
require_once dirname(__FILE__).'/events/location_main_sim_viewpost.php';
require_once dirname(__FILE__).'/events/location_main_sim_missions_one.php';
require_once dirname(__FILE__).'/events/location_main_sim_listposts.php';
require_once dirname(__FILE__).'/events/parser_parse_string_nova_missionpost.php';
