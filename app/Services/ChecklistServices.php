<?php namespace App\Services;

use App\Http\Models\{
  Checklist,
};

use Illuminate\Support\Facades\{
  Cache
};


class ChecklistServices { 

    public static function insert_checklist(Array $checklist_details){
        
        $checklist_id =  Checklist::upsert_checklist($checklist_details);

        
       return $checklist_id;
    }

    public static function update_checklist(Array $checklist_details, Array $where){
        
        Checklist::upsert_checklist($checklist_details, $where); 

        return true;
    }

    public static function get_checklists_by_id_and_org_id($id, $org_id){

        return Checklist::get_checklists_by_id_and_org_id($id, $org_id);

    }

    public static function get_checklists_by_org_id($org_id){

      return Checklist::get_checklists_by_org_id($org_id);

  }

    public static function get_checklist_form($id){

      return Checklist::get_checklist_form($id);

    }

    public static function delete_checklist_by_id(Array $where){

       return Checklist::delete_checklist($where);
    }
}