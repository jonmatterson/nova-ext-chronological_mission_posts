<?php

$this->event->listen(['location', 'view', 'data', 'admin', 'write_missionpost'], function($event){

  $id = (is_numeric($this->uri->segment(3))) ? $this->uri->segment(3) : false;
  $post = $id ? $this->posts->get_post($id) : null;
  
  $timepickerOptions = [
    'timeFormat' => 'HHmm',
    'defaultTime' =>  $post ? $post->post_chronological_mission_post_time : '0000'
  ];

  $this->config->load('extensions');
  $extensionsConfig = $this->config->item('extensions');
  
  if(!empty($extensionsConfig['chronological_mission_posts']['timepicker_options'])){
    foreach($extensionsConfig['chronological_mission_posts']['timepicker_options'] as $key => $value){
      $timepickerOptions[$key] = $value;
    }
  }
  
  $editDayLabel = isset($extensionsConfig['chronological_mission_posts']['label_edit_day'])
                        ? $extensionsConfig['chronological_mission_posts']['label_edit_day']
                        : 'Mission Day';

  $editTimeLabel = isset($extensionsConfig['chronological_mission_posts']['label_edit_time'])
                        ? $extensionsConfig['chronological_mission_posts']['label_edit_time']
                        : 'Time';

  $viewPrefixLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_prefix'])
                        ? $extensionsConfig['chronological_mission_posts']['label_view_prefix']
                        : 'Mission Day';

  $viewConcatLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_concat'])
                        ? $extensionsConfig['chronological_mission_posts']['label_view_concat']
                        : 'at';

  $viewSuffixLabel = isset($extensionsConfig['chronological_mission_posts']['label_view_suffix'])
                        ? $extensionsConfig['chronological_mission_posts']['label_view_suffix']
                        : '';
  
  switch($this->uri->segment(4)){
    case 'view':
      $event['data']['inputs']['timeline']['value'] = $viewPrefixLabel.' '.$post->post_chronological_mission_post_day.' '.$viewConcatLabel.' '.$post->post_chronological_mission_post_time.' '.$viewSuffixLabel;
      break;
    default:
      $event['data']['label']['chronological_mission_post_day'] = $editDayLabel;
      $event['data']['inputs']['chronological_mission_post_day'] = array(
        'name' => 'chronological_mission_post_day',
        'id' => 'chronological_mission_post_day',
        'onkeypress' => 'return (function(evt)
        {
           var charCode = (evt.which) ? evt.which : event.keyCode
           if (charCode > 31 && (charCode < 48 || charCode > 57))
              return false;

           return true;
        })(event)',
        'value' => $post ? $post->post_chronological_mission_post_day : '1'
      );
      
      $event['data']['label']['chronological_mission_post_time'] = $editTimeLabel;
      $event['data']['inputs']['chronological_mission_post_time'] = array(
        'name' => 'chronological_mission_post_time',
        'id' => 'chronological_mission_post_time',
        'data-timepicker' => str_replace('"', '&quot;', json_encode($timepickerOptions)),
        'value' => $post ? $post->post_chronological_mission_post_time : '0000'
      );
  }
  
});

$this->event->listen(['location', 'view', 'output', 'admin', 'write_missionpost'], function($event){

  switch($this->uri->segment(4)){
    case 'view':
      break;
    default:
      $event['output'] .= $this->extension['jquery']['generator']
                      ->select('#timeline')->closest('p')
                      ->after(
                        $this->extension['chronological_mission_posts']
                             ->view('write_missionpost', $this->skin, 'admin', $event['data'])
                      );

      $event['output'] .= $this->extension['jquery']['generator']
                               ->select('#timeline')->closest('p')->remove();
 }
                  
});
