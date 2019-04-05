<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_listposts'], function($event){
  $mission_id = $this->uri->segment(4, false, true);
  if($mission_id === false){
    // SKIP -- extension does not currently ordering across multiple missions
  }else{
      
      
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
                        
    $offset = $this->uri->segment(5, 0, true);
    
    $event['data']['posts'] = [];
    
    $this->db->from('posts');
    $this->db->where('post_mission', $mission_id);
    $this->db->where('post_status', 'activated');
    $this->db->order_by('post_chronological_mission_post_day', 'asc');
    $this->db->order_by('post_chronological_mission_post_time', 'asc');
    $this->db->order_by('post_date', 'asc');
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
        $event['data']['posts'][] = [
          'id' => $post->post_id,
          'title' => $post->post_title,
          'author' => $this->char->get_authors($post->post_authors, true, true),
          'date' => $timeline,
          'location' => $post->post_location,
          'mission' => $this->mis->get_mission($post->post_mission, 'mission_title'),
          'mission_id' => $mission_id
        ];
      }
    }

  }
});
