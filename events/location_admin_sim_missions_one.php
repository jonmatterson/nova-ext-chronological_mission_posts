<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_missions_one'], function($event){
  
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
  
  $event['data']['posts'] = [];

  $this->db->from('posts');
  $this->db->where('post_mission', $event['data']['mission']);
  $this->db->where('post_status', 'activated');
  $this->db->order_by('post_chronological_mission_post_day', 'desc');
  $this->db->order_by('post_chronological_mission_post_time', 'desc');
  $this->db->order_by('post_date', 'desc');
  $this->db->limit(25, 0);
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
        $event['data']['posts'][] = [
            'id' => $post->post_id,
            'title' => $post->post_title,
            'authors' => $this->char->get_authors($post->post_authors, true, true),
            'timeline' => $timeline,
            'location' => $post->post_location,
        ];
    }
  }
  
});

$this->event->listen(['template', 'render', 'data', 'sim', 'missions'], function($event){
  $event['data']['javascript'] .= $this->extension['chronological_mission_posts']->inline_css('sim_missions', 'main', $data);
});

$this->event->listen(['location', 'view', 'output', 'main', 'sim_missions_one'], function($event){
    
  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');
  
  $labelReadStoryButton = isset($extensionsConfig['chronological_mission_posts']['label_mission_read_story_button'])
    ? $extensionsConfig['chronological_mission_posts']['label_mission_read_story_button']
    : 'Read Story';
    
  $labelReadStoryLink = isset($extensionsConfig['chronological_mission_posts']['label_mission_read_story_link'])
    ? $extensionsConfig['chronological_mission_posts']['label_mission_read_story_link']
    : 'Read Story &raquo;';
  
  
  if($labelReadStoryButton){
    $event['output'] .= $this->extension['jquery']['generator']
                             ->select('.page-head')
                             ->first()
                             ->before('<a href="'.$this->extension['chronological_mission_posts']->url('sim/readposts/mission/'.$event['data']['mission']).'" class="chronological_mission_posts--sim_missions--read-story"><button>'.$labelReadStoryButton.'</button></a>');
  }
  
  if($labelReadStoryLink){
    $event['output'] .= $this->extension['jquery']['generator']
                             ->select('#two h2')
                             ->first()
                             ->after('<a href="'.$this->extension['chronological_mission_posts']->url('sim/readposts/mission/'.$event['data']['mission']).'"" class="bold">'.$labelReadStoryLink.'</a>');
  }
                           
});
