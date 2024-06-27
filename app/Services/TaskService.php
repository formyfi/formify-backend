<?php namespace App\Services;

use App\Http\Models\{
  Part, Checklist, Task
};

use Illuminate\Support\Facades\{
  Cache
};


class TaskService { 

    public static function insert_part(Array $part_details){
        $v_numbers = '';
        if(!empty($part_details['v_numbers'])) $v_numbers = $part_details['v_numbers'];
        unset($part_details['v_numbers']);

        $part_id =  Part::upsert_part($part_details);

        if(!empty($part_id) && !empty($v_numbers)){
          $v_numbers = explode(',', $v_numbers);
            foreach ($v_numbers as $key => $v_number) {
              Part::upsert_part_vnumber(['part_id' => $part_id, 'v_num' => $v_number]);
            }
        } 
       return $part_id;
    }

    public static function update_part(Array $part_details, Array $where){
      
        $v_numbers = '';
        if(!empty($part_details['v_numbers'])) $v_numbers = $part_details['v_numbers'];
        unset($part_details['v_numbers']);
        
        Part::upsert_part($part_details, $where);

        if(!empty($where['id']) && !empty($v_numbers)){
          $v_numbers = explode(',', $v_numbers);
          foreach ($v_numbers as $key => $v_number) {
            Part::upsert_part_vnumber(['part_id' => $where['id'], 'v_num' => $v_number]);
          }
        } 

        return true;
    }

    public static function get_task_form($org_id, $station_value, $part_value, $v_number){

      $form_value =  Task::get_task_entry($station_value, $part_value, $v_number);
      $form_json = Checklist::get_checklist_form(0, $org_id, $station_value, $part_value);

      return ['form_value' => !empty($form_value) ? $form_value : null, 'form_json' => (!empty($form_json) && empty($form_json->is_draft)) ? $form_json->form_json : null, 'form_name' => !empty($form_json) ? $form_json->name : null,'form_id' => !empty($form_json) ? $form_json->id : null];

    }

    public static function delete_part_by_id(Array $where){

       return Part::delete_part($where);
    }
}