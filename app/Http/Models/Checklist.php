<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Checklist extends Model { 

    //Get Queries

    //Set Queries

    public static function upsert_checklist(Array $update_params, Array $where_params=[]){

        if(empty($where_params)){
            $insert_id = DB::table('forms')
            ->insertGetId($update_params);
            return ($insert_id) ? $insert_id : false;
        } else {

            DB::table('forms')
            ->where($where_params)
            ->update($update_params);

            return true;
        }

    }

    public static function update_template(Array $update_params, Array $where_params){

            DB::table('form_templates')
            ->where($where_params)
            ->update($update_params);

            return true;
      

    }
    
    public static function insert_template($update_params){

      
            $insert_id = DB::table('form_templates')
            ->insertGetId($update_params);
            return ($insert_id) ? $insert_id : false;
        

    }

    public static function get_checklists_by_id($id){

        $results = DB::select("SELECT f.* FROM forms f WHERE f.id = ?", [$id]);
                    
        return (count($results) > 0) ? $results : false;
    }

    public static function get_checklists_by_name_and_org_id($name, $org_id){

        $results = DB::select("SELECT f.* FROM forms f WHERE f.name = ? AND f.org_id = ?", [$name, $org_id]);
                    
        return (count($results) > 0) ? $results : false;
    }

    public static function get_checklists_by_org_id($org_id){

       $results = DB::select("SELECT s.*, st.name as station_name, pt.name as part_name FROM forms s LEFT JOIN stations st ON st.id=s.station_id LEFT JOIN parts pt ON pt.id=s.part_id  WHERE s.org_id = ? GROUP BY s.id, st.id, pt.id", [$org_id]);
            
        return (count($results) > 0) ? $results : false;
    }

    public static function get_templates(Int $org_id){

    $results = DB::select("SELECT fs.* FROM form_templates fs WHERE fs.org_id = ?", [$org_id]);
            
        return (count($results) > 0) ? $results : false;
    }

    
    public static function check_if_station_part_allocated(String $unique_id, $org_id){
        
        $results = DB::select("SELECT f.id FROM forms f WHERE f.unique_id = ? AND f.org_id = ?", [$unique_id, $org_id]);
  
        return (count($results) > 0) ? $results[0] : false;
      }

    public static function get_checklist_form($id, $org_id = null, Int $station_id = null, Int $part_id = null){
        $where = "WHERE id = ?";
        $params = [$id];
        if(!empty($station_id) && !empty($part_id)){
            $where = "WHERE org_id = ? station_id = ? AND part_id = ?";
            $params = [$org_id, $station_id, $part_id];
        }
        $results = DB::select("SELECT id, name, form_json, is_draft FROM forms $where",  $params);
        
        return (count($results) > 0) ? $results[0] : false;
    }

    public static function delete_checklist(Array $where){
    
        DB::table('forms')
            ->where($where)
            ->delete();

            return true;
    }

}