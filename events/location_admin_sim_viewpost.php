<?php 

$this->event->listen(['location', 'view', 'data', 'main', 'sim_viewpost'], function($event){

  $id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : false;
  $post = $id ? $this->posts->get_post($id) : null;

  if($post && empty($post->post_timeline)){

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
    
    $event['data']['timeline'] = $viewPrefixLabel.' '.$post->post_chronological_mission_post_day.' '.$viewConcatLabel.' '.$post->post_chronological_mission_post_time.' '.$viewSuffixLabel;
  }
  
});
