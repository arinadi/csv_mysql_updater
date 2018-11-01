<?php
ini_set('max_execution_time', 3000);
include("config.php");
function db_connect(){
	$connection_server=mysqli_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,DB_DB);
	if(!$connection_server){
	   return false;	
	}
	
	return $connection_server;
}	

function escape_data($data) { 
	$dbc = db_connect();
	$data = stripslashes($data);	
	return mysqli_real_escape_string($dbc, trim($data));
}

function get_tables(){
	$dbc = db_connect();
	$result = mysqli_query($dbc,"SHOW tables FROM ".$dbname);
		$tables = array();
		$q = 0;
		$v = 0;
		while ($row = mysqli_fetch_array($result)) {
			//Fetch all table 
			$tables['tables'][$q] = array("table_name" => $row[0]);
				//If you want to pull more details for the table then set as true
				if($deep){
					//This is to fetch fields
					$query_level = mysqli_query($dbc,"SHOW columns FROM ".$row[0]);
					if($query_level){
						$fields = array();
						while($fields = mysqli_fetch_array($query_level)){
							$tables['tables'][$q]['table_fields'][$v] = $fields;
							$v++;
						}
					}
				}
			$q++;
		}	
		return $tables;
}

function get_table_structure($tablename){
	$dbc = db_connect();
	$tables = array();
		$query_level = mysqli_query($dbc, "SHOW columns FROM ".$tablename);
				if($query_level){
						$v = 0;
						$fields = array();
						while($fields = mysqli_fetch_array($query_level)){
							$tables[$v] = $fields;
							$v++;
						}
				}
		return $tables;
}

function update_simple($table,$data,$where) {
		 $dbc = db_connect();
		 $query = "UPDATE $table SET $data $where"; 
		 $result = mysqli_query($dbc,$query)or die("query failed ".mysqli_error($dbc));
			//$result = db_results($result);
			//var_dump($query);
			//exit();
		return mysqli_affected_rows($dbc);
      }
 function db_results($result){
		$res_array = array();
		for($count=0;$row = mysqli_fetch_array($result);$count++)

		{
          	$res_array[$count] = $row;
	    }

		return $res_array;
	}

