<?php namespace App\Http\Models;

use App\Http\Models\Model;
use DB;



class Task extends Model { 

    //Get Queries

    public static function get_task_entry(Int $station_value, Int $part_value, $v_number){

        $form_data = DB::select("SELECT cd.form_data, cv.id FROM checklist_vnum_record cv JOIN checklist_data cd ON (cd.checklist_vnum_record_id = cv.id) WHERE cv.station_id = ? AND cv.part_id = ? AND cv.vnum_id=?", [$station_value, $part_value, $v_number]);

        return (count($form_data) > 0) ? $form_data[0]->form_data: false;
    }
    public static function check_task_entry(Int $station_value, Int $part_value, $v_number){

        $form_data = DB::select("SELECT cv.id FROM checklist_vnum_record cv WHERE cv.station_id = ? AND cv.part_id = ? AND cv.vnum_id=?", [$station_value, $part_value, $v_number]);

        return (count($form_data) > 0) ? $form_data[0]->id: false;
    }

    public static function get_total_task_list(Int $org_id, Int $user_id){

        $list = DB::select("SELECT COUNT(*) AS total_records
        FROM checklist_vnum_record cv
        LEFT JOIN forms fs ON fs.id = cv.form_id
        WHERE cv.org_id = ? AND cv.is_completed = 1 AND fs.form_json IS NOT NULL AND EXISTS (SELECT 1 FROM user_station us WHERE us.station_id = cv.station_id AND us.user_id = ?) ORDER BY cv.updated_at DESC", [$org_id, $user_id]);

    return (count($list) > 0) ? $list[0]->total_records : false;
    }

    public static function get_total_task_list_with_search_text(Int $org_id, Int $user_id, $searchText){

        $searchText = "%{$searchText}%";

        $list = DB::select("SELECT COUNT(*) AS total_records
        
        FROM checklist_vnum_record cv 
        LEFT JOIN checklist_data cd ON cd.checklist_vnum_record_id = cv.id
        LEFT JOIN stations s ON s.id = cv.station_id
        LEFT JOIN parts p ON p.id = cv.part_id
        LEFT JOIN forms fs ON fs.id = cv.form_id
        LEFT JOIN users u ON u.id = cd.last_updated_id
        WHERE cv.org_id = ? AND fs.form_json IS NOT NULL AND cv.is_completed = 1
        AND EXISTS (SELECT 1 FROM user_station us WHERE us.station_id = cv.station_id AND us.user_id = ?)
        AND (s.name LIKE ? OR p.name LIKE ? OR cv.vnum_id LIKE ? OR fs.name LIKE ?)
        ORDER BY cv.updated_at DESC", [$org_id, $user_id, $searchText, $searchText, $searchText, $searchText]);

    return (count($list) > 0) ? $list[0]->total_records : false;
    }

    public static function get_task_list(Int $org_id, Int $user_id, $searchText, Int $perPage = 10, Int $page = 1, $super_user_ind = 0, $filters)
{

    if ($searchText && $searchText != '') {
        $page = 1;
    }
   $offset = ($page - 1) * $perPage;
    $where = '';
    if(!$super_user_ind) $where = " AND EXISTS (SELECT 1 FROM user_station us WHERE us.station_id = cv.station_id AND us.user_id = $user_id)";
    if(!empty($filters)){
        if(!empty($filters['station_name'])){
            $station_name = $filters['station_name'];
            $where .= " AND s.name = '$station_name'";
        }
        if(!empty($filters['part_name'])){
            $part_name = $filters['part_name'];
            $where .= " AND p.name = '$part_name'";
        }
        if(!empty($filters['form_name'])){
            $form_name = $filters['form_name'];
            $where .= " AND fs.name = '$form_name'";
        }
        if(!empty($filters['compliance_ind'])){
            if($filters['compliance_ind'] == 'Yes') $where .= " AND cv.compliance_ind = 1";
            if($filters['compliance_ind'] == 'No') $where .= " AND cv.compliance_ind = 0";
            
        }
    }
    if($searchText && $searchText != ''){
        $searchText = "%{$searchText}%";

        $list = DB::select("SELECT s.name AS station_name, p.name AS part_name, cv.vnum_id, cv.form_id, IF(cv.compliance_ind = 1, 'Yes', 'No') AS compliance_ind, CONCAT(u.first_name, ' ' ,u.last_name) AS last_updated_by_name, 
            cv.part_id, cv.station_id, cd.last_updated_id, 
            cd.form_data, fs.form_json, fs.name AS form_name
        
        FROM checklist_vnum_record cv 
        LEFT JOIN checklist_data cd ON cd.checklist_vnum_record_id = cv.id
        LEFT JOIN stations s ON s.id = cv.station_id
        LEFT JOIN parts p ON p.id = cv.part_id
        LEFT JOIN forms fs ON fs.id = cv.form_id
        LEFT JOIN users u ON u.id = cd.last_updated_id
        WHERE cv.org_id = ? AND fs.form_json IS NOT NULL AND cv.is_completed = 1
        AND (
            s.name LIKE ? OR
            p.name LIKE ? OR
            cv.vnum_id LIKE ? OR
            fs.name LIKE ?
        ) $where
        ORDER BY cd.updated_at DESC
        LIMIT ? OFFSET ?", [
            $org_id,
            $searchText,
            $searchText,
            $searchText,
            $searchText,
            $perPage,
            $offset
        ]);
    } else {
        $list = DB::select("SELECT s.name AS station_name, p.name AS part_name, cv.vnum_id, cv.form_id, IF(cv.compliance_ind = 1, 'Yes', 'No') AS compliance_ind, CONCAT(u.first_name, ' ' ,u.last_name) AS last_updated_by_name, 
            cv.part_id, cv.station_id, cd.last_updated_id, 
            cd.form_data, fs.form_json, fs.name AS form_name
        
        FROM checklist_vnum_record cv 
        LEFT JOIN checklist_data cd ON cd.checklist_vnum_record_id = cv.id
        LEFT JOIN stations s ON s.id = cv.station_id
        LEFT JOIN parts p ON p.id = cv.part_id
        LEFT JOIN forms fs ON fs.id = cv.form_id
        LEFT JOIN users u ON u.id = cd.last_updated_id
        WHERE cv.org_id = ? AND fs.form_json IS NOT NULL AND cv.is_completed = 1 $where 
        ORDER BY cd.updated_at DESC
        LIMIT ? OFFSET ?", [$org_id, $perPage, $offset]);
    }
    

    return (count($list) > 0) ? $list : false;
}

    public static function get_full_tasklist_data(Int $org_id, $start_date, $end_date){
        
        if(!empty($start_date) && !empty($end_date)){
            $start_date = new \DateTime($start_date);
            $start_date = $start_date->format('Y-m-d');

            $end_date = new \DateTime($end_date);
            $end_date = $end_date->format('Y-m-d');

            $list = DB::select("SELECT
            DATE_FORMAT(dates.date, '%m/%d') AS date,
            COALESCE(total_records, 0) AS total_records,
            COALESCE(compliant_records, 0) AS compliant_records,
            COALESCE(non_compliant_records, 0) AS non_compliant_records
        FROM
            (
                SELECT DATE(DATE_ADD(?, INTERVAL n.num DAY)) AS date
                FROM
                    (
                        SELECT (t2.i * 10 + t1.i) num
                        FROM
                            (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                            (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
                    ) n
                WHERE
                    DATE(DATE_ADD(?, INTERVAL n.num DAY)) <= ?
            ) dates
        LEFT JOIN
            (
                SELECT
                    DATE(created_at) AS date,
                    COUNT(*) AS total_records,
                    SUM(compliance_ind) AS compliant_records,
                    SUM(1 - compliance_ind) AS non_compliant_records
                FROM
                    checklist_vnum_record
                WHERE
                    org_id = ? AND
                    created_at >= ? AND
                    created_at <= ?
                GROUP BY
                    DATE(created_at)
            ) records ON dates.date = records.date
        ORDER BY
            dates.date", [ $start_date, $start_date, $end_date, $org_id, $start_date, $end_date]);

        return (count($list) > 0) ? $list : [];
        
        }
}

public static function get_station_tasklist_data(Int $org_id, $start_date, $end_date){
        
    if(!empty($start_date) && !empty($end_date)){
        $start_date = new \DateTime($start_date);
        $start_date = $start_date->format('Y-m-d');

        $end_date = new \DateTime($end_date);
        $end_date = $end_date->format('Y-m-d');

        $list = DB::select("SELECT
        s.id AS station_id,
        s.name AS station_name,
        MIN(c.created_at) AS date,
        COALESCE(COUNT(c.created_at), 0) AS total_records
    FROM
        stations s
    CROSS JOIN
        (
            SELECT DATE(DATE_ADD(?, INTERVAL n.num DAY)) AS date
            FROM
                (
                    SELECT (t2.i * 10 + t1.i) num
                    FROM
                        (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t1,
                        (SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) t2
                ) n
            WHERE
                DATE(DATE_ADD(?, INTERVAL n.num DAY)) <= ?
        ) dates
    LEFT JOIN
        checklist_vnum_record c ON DATE(c.created_at) = dates.date AND c.station_id = s.id AND c.org_id = ?
    WHERE
        c.org_id = ?
        AND c.created_at >= ?
        AND c.created_at <= ?
    GROUP BY
        s.id, dates.date
    ORDER BY
        s.id, dates.date
    ", [ $start_date, $start_date, $end_date, $org_id,$org_id, $start_date, $end_date]);

    return (count($list) > 0) ? $list : [];
    
    }
}

public static function lock_form_by_user_id($update_params){
    $exist = DB::select("SELECT tl.locked_by_user_id FROM task_locker tl WHERE tl.locked_by_user_id = ? AND tl.station_id = ? AND tl.part_id = ? AND tl.part_vnum = ? AND lock_ind = 1", [$update_params['locked_by_user_id'], $update_params['station_id'], $update_params['part_id'], $update_params['part_vnum']]);

        if(empty($exist)){
            DB::table('task_locker')
            ->insert($update_params);

            return true;
        }
           
}

public static function unlock_form_by_user_id($user_id){
        
    
        $where_params = ['locked_by_user_id' => $user_id];
        $update_params = ['lock_ind' => 0];

        DB::table('task_locker')
        ->where($where_params)
        ->update($update_params);

        return true;
}

public static function check_if_form_is_locked($user_id, $station_id, $part_id, $part_vnum){
        
    $exist = DB::select("SELECT tl.locked_by_user_id, CONCAT(u.first_name,' ',u.last_name) AS locked_by_user_name FROM task_locker tl JOIN users u ON u.id = tl.locked_by_user_id WHERE tl.locked_by_user_id != ? AND tl.station_id = ? AND tl.part_id = ? AND tl.part_vnum = ? AND lock_ind = 1", [$user_id, $station_id, $part_id, $part_vnum]);
    return (count($exist) > 0) ? $exist[0] : [];
}

public static function get_total_stations_inspections(Int $org_id){

        $list = DB::select("SELECT
        s.id AS station_id,
        s.name AS station_name,
        COUNT(*) AS total_records,
        SUM(compliance_ind) AS compliant_records,
        SUM(1 - compliance_ind) AS non_compliant_records
    FROM checklist_vnum_record c
    JOIN stations s ON (c.station_id = s.id)
    WHERE c.org_id = ?
    GROUP BY s.id
    ORDER BY s.id", [$org_id]);

    return (count($list) > 0) ? $list : [];
}
    //Set Queries

    public static function update_checklist_task_data(Array $update_params, Array $where_params){

        DB::table('checklist_data')
        ->where($where_params)
        ->update($update_params);

        return true;

    }

    public static function insert_checklist_task_record(Array $update_params){

            $user_id = $update_params['last_updated_id'];
            unset($update_params['last_updated_id']);
        
            $exist = DB::select("SELECT id FROM checklist_vnum_record WHERE form_id = ? AND vnum_id = ? AND org_id = ?", [$update_params['form_id'], $update_params['vnum_id'], $update_params['org_id']]);
            $station_name = DB::select("SELECT st.name FROM stations st WHERE id = ? AND org_id = ?", [$update_params['station_id'], $update_params['org_id']]);
            $user_name = DB::select("SELECT CONCAT(first_name, ' ', last_name) AS name FROM users WHERE id = ? AND org_id = ?", [ $user_id, $update_params['org_id']]);
            $complaint = empty($update_params['is_completed']) ? '' : ($update_params['compliance_ind'] ? '[Compliant]' : '[Non Compliant]');
            $data = [];

           if(empty($exist)){
                $insert_id = DB::table('checklist_vnum_record')
                ->insertGetId($update_params);
                $text = empty($update_params['is_completed']) ? 'Inspection progress saved at '.$station_name[0]->name.' By '.$user_name[0]->name : 'A new inspection completed at '.$station_name[0]->name.' By '.$user_name[0]->name.'. '.$complaint;
                $data = [
                    'form_vnumber_id' => $update_params['vnum_id'],
                    'type' => 'new_form_submitted',
                    'text' => $text,
                    'org_id' => $update_params['org_id'],
                    'station_id' => $update_params['station_id'],
                    'user_id' =>  0,
                ];
                $timelineId = DB::table('timelines')->insertGetId($data);
                $timeline = DB::table('timelines')->find($timelineId);
                
                return $insert_id;
           } else {
                $where_params = ['id' => $exist[0]->id];
                DB::table('checklist_vnum_record')
                ->where($where_params)
                ->update($update_params);
                $text = empty($update_params['is_completed']) ? 'Inspection progress saved at '.$station_name[0]->name.' By '.$user_name[0]->name : 'A new inspection completed at '.$station_name[0]->name.' By '.$user_name[0]->name.'. '.$complaint;

                $data = [
                    'form_vnumber_id' => $update_params['vnum_id'],
                    'type' => 'new_form_submitted',
                    'text' => $text,
                    'org_id' => $update_params['org_id'],
                    'station_id' => $update_params['station_id'],
                    'user_id' =>  0,
                ];
                $timelineId = DB::table('timelines')->insertGetId($data);
                $timeline = DB::table('timelines')->find($timelineId);

                return $exist[0]->id;
           } 

          
                       
    }

    public static function insert_checklist_task_data(Array $update_params){

        $exist = DB::select("SELECT id FROM checklist_data WHERE checklist_vnum_record_id = ?", [$update_params['checklist_vnum_record_id']]);
        
        if(!empty($exist)){
            $where_params = ['checklist_vnum_record_id' => $update_params['checklist_vnum_record_id']];
            DB::table('checklist_data')
            ->where($where_params)
            ->update($update_params);

            return true;
        }else{
            $insert_id = DB::table('checklist_data')
            ->insertGetId($update_params);
            
            return $insert_id;
        }
        
      
    }

}