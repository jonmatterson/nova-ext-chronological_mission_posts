<?php 

$this->event->listen(['location', 'view', 'data', 'admin', 'manage_posts_edit'], function($event){
  
  $id = (is_numeric($this->uri->segment(4))) ? $this->uri->segment(4) : false;
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
  
});

$this->event->listen(['location', 'view', 'output', 'admin', 'manage_posts_edit'], function($event){

  $event['output'] .= $this->extension['jquery']['generator']
                  ->select('[name="post_timeline"]')->closest('p')
                  ->after(
                    $this->extension['chronological_mission_posts']
                         ->view('write_missionpost', $this->skin, 'admin', $event['data'])
                  );

  $event['output'] .= $this->extension['jquery']['generator']
                           ->select('[name="post_timeline"]')->closest('p')->remove();
                  
});
