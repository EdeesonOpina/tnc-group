<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Hash;
use Auth;
use DB;

class DatabaseController extends Controller
{
    public function confirm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ]);
        
        if($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        // check the password if it matches
        if (!Hash::check($request->password, auth()->user()->password)) {
            $request->session()->flash('error', 'Password did not match');
            return back();
        } else {
            return redirect()->route('admin.sql.export');
        }
    }

    public function export()
    {
        $DbName             = env('DB_DATABASE');
        $get_all_table_query = "SHOW TABLES ";
        $result = DB::select(DB::raw($get_all_table_query));

        $prep = "Tables_in_$DbName";
        foreach ($result as $res){
            $tables[] =  $res->$prep;
        }

        $connect = DB::connection()->getPdo();

        $get_all_table_query = "SHOW TABLES";
        $statement = $connect->prepare($get_all_table_query);
        $statement->execute();
        $result = $statement->fetchAll();


        $output = '';
        foreach($tables as $table)
        {
            $show_table_query = "SHOW CREATE TABLE " . $table . "";
            $statement = $connect->prepare($show_table_query);
            $statement->execute();
            $show_table_result = $statement->fetchAll();

            foreach($show_table_result as $show_table_row)
            {
                $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
            }
            $select_query = "SELECT * FROM " . $table . "";
            $statement = $connect->prepare($select_query);
            $statement->execute();
            $total_row = $statement->rowCount();

            for($count=0; $count<$total_row; $count++)
            {
                $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                $table_column_array = array_keys($single_result);
                $table_value_array = array_values($single_result);
                $output .= "\nINSERT INTO $table (";
                $output .= "" . implode(", ", $table_column_array) . ") VALUES (";
                $output .= "'" . implode("','", $table_value_array) . "');\n";
            }
        }
        $file_name = 'database_backup_on_' . date('y-m-d') . '.sql';
        $file_handle = fopen($file_name, 'w+');
        fwrite($file_handle, $output);
        fclose($file_handle);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file_name));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_name));
        ob_clean();
        flush();
        readfile($file_name);
        unlink($file_name);

        $request->session()->flash('success', 'Database successfully exported');
        return back();
    }
}
