<?php 

$this->event->listen(['parser', 'parse_string', 'output', 'write', 'missionpost'], function($event){
    
    if(!empty($this->input->post('chronological_mission_post_day')) && !empty($this->input->post('chronological_mission_post_time'))){
        
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
            
            $timelineValue = $viewPrefixLabel.' '.$this->input->post('chronological_mission_post_day').' '.$viewConcatLabel.' '.$this->input->post('chronological_mission_post_time').' '.$viewSuffixLabel;
            
            $event['output'] = preg_replace(
                '/'.preg_quote(lang('email_content_post_timeline')).'.*\<br \/\>/', 
                lang('email_content_post_timeline').' '.$timelineValue.'<br />', 
                $event['output'], 
                1
            );
        
    }
    
});
