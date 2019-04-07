<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once MODPATH.'core/libraries/Nova_controller_main.php';

class __extensions__chronological_mission_posts__sim extends Nova_controller_main
{
	public function __construct()
	{
		parent::__construct();

		$this->_regions['nav_sub'] = Menu::build('sub', 'sim');
	}
  
  public function readposts()
  {
	// load the resources
	$this->load->model('posts_model', 'posts');
	$this->load->model('missions_model', 'mis');
	$this->load->library('pagination');

	$mission_id = $this->uri->rsegment(4, false, true);

	if($mission_id === false)
	{
		// SKIP -- extension does not currently ordering across multiple missions
	}
	else
	{
		$this->config->load('extensions');
		$extensionsConfig = $this->config->item('extensions');

		$viewPrefixLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_prefix'])
							? $extensionsConfig['chronological_mission_posts']['label_view_prefix']
							: 'Mission Day';

		$viewConcatLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_concat'])
							? $extensionsConfig['chronological_mission_posts']['label_view_concat']
							: 'at';

		$viewSuffixLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_suffix'])
							? $extensionsConfig['chronological_mission_posts']['label_view_suffix']
							: '';

	    $postOrderColumnFallback = isset($extensionsConfig['chronological_mission_posts']['post_order_column_fallback'])
	                        ? $extensionsConfig['chronological_mission_posts']['post_order_column_fallback']
	                        : 'post_date';
		
		$data = [
			'mission_id' => $mission_id,
			'mission_url' => site_url('sim/missions/id/'.$mission_id),
			'title' => ucfirst(lang('global_missions')).' - '.$this->mis->get_mission($mission_id, 'mission_title'),
			'labels' => [
				'characters' => isset($extensionsConfig['chronological_mission_posts']['label_story_character_list'])
									? $extensionsConfig['chronological_mission_posts']['label_story_character_list']
									: 'Featuring:',
				'location' => isset($extensionsConfig['chronological_mission_posts']['label_story_location'])
									? $extensionsConfig['chronological_mission_posts']['label_story_location']
									: 'Location:',
				'timeline' => isset($extensionsConfig['chronological_mission_posts']['label_story_timeline'])
									? $extensionsConfig['chronological_mission_posts']['label_story_timeline']
									: 'On:',
				'mission_link' => isset($extensionsConfig['chronological_mission_posts']['label_story_back_to_mission'])
									? $extensionsConfig['chronological_mission_posts']['label_story_back_to_mission']
									: 'View Mission Details &raquo;'
			]
		];

		$offset = $this->uri->segment(7, 0, true);

		// initialize the pagination library
		$this->pagination->initialize([
			'base_url' => $this->extension['chronological_mission_posts']->url('sim/readposts/mission/'.$mission_id),
			'total_rows' => $this->posts->count_all_posts($mission),
			'per_page' => $this->options['list_posts_num'],
			'uri_segment' => 7,
			'full_tag_open' => '<p class="fontMedium bold">',
			'full_tag_close' => '</p>'
		]);

		$data['pagination'] = $this->pagination->create_links();
		      
		$data['posts'] = [];

		$this->db->from('posts');
		$this->db->where('post_mission', $mission_id);
		$this->db->where('post_status', 'activated');
		$this->db->order_by('post_chronological_mission_post_day', 'asc');
		$this->db->order_by('post_chronological_mission_post_time', 'asc');
		$this->db->order_by($postOrderColumnFallback, 'asc');
		$this->db->limit($this->pagination->per_page, $offset);
		$posts = $this->db->get();

		if ($posts->num_rows() > 0)
		{
			foreach ($posts->result() as $post)
			{
				if(empty($post->post_timeline)){
				  $timeline = $viewPrefixLabel.' '.$post->post_chronological_mission_post_day.' '.$viewConcatLabel.' '.$post->post_chronological_mission_post_time.' '.$viewSuffixLabel;
				}else{
				  $timeline = $post->post_timeline;
				}
				
				$data['posts'][] = [
					'id' => $post->post_id,
					'title' => $post->post_title,
					'characters' => $this->char->get_authors($post->post_authors, true, true),
					'timeline' => $timeline,
					'location' => $post->post_location,
					'content' => $post->post_content
				];
			}
		}

		$this->_regions['javascript'] .= $this->extension['chronological_mission_posts']->inline_css('sim_readposts', 'main', $data);
		$this->_regions['title'] .= 'Read Story';
		$this->_regions['content'] = $this->extension['chronological_mission_posts']
		                                ->view('sim_readposts', $this->skin, 'main', $data);
		                                
		Template::assign($this->_regions);

		Template::render();
    }
  }
}
