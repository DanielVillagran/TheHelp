<?php

class ReviewFunctions{

	public static function required($data){
		return (!isset($data) || trim($data)==='');
	}

	public static function ExistEmail($data, $array = array()){
        	$link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
                $select = "select * from users where lower(email) = lower('".$data."')";
                if($array['type'] == 'edit'){
                        $select .= " and id != ".$array['user_id'];
                }
                $result = pg_query($link, $select);
                if(pg_numrows($result) == 0){
                	return false;
                } else {
                	return true;
                }
	}

        public static function ExistDriverLicense($data, $array = array()){
                $link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
                $select = "select * from clients where lower(dl_number) = lower('".$data."')";
                if($array['type'] == 'edit'){
                        $select .= " and user_id != ".$array['user_id'];
                }
                $result = pg_query($link, $select);
                if(pg_numrows($result) == 0){
                        return false;
                } else {
                        return true;
                }
        }

	public static function ExistUsername($data, $array = array()){
        	$link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
                $select = "select * from users where lower(username) = lower('".$data."')";
                if($array['type'] == 'edit'){
                        $select .= " and id != ".$array['user_id'];
                }
                $result = pg_query($link, $select);
                if(pg_numrows($result) == 0){
                	return false;
                } else {
                	return true;
                }
	}

        public static function ExistAgency($data, $array = array()){
                $link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
                $select = "select * from agencies where lower(producer_code) = lower('".$data."')";
                if($array['type'] == 'edit'){
                        $select .= " and ".$array['condition'];
                }
                $result = pg_query($link, $select);
                if(pg_numrows($result) == 0){
                        return false;
                } else {
                        return true;
                }
        }

        public static function ExistVin($data, $array = array()){
                $link = pg_Connect("host=tron.antfarm.mx dbname=alexandria user=postgres password=@p0stgr3s!");
                $select = "select * from vehicles where lower(vin) = lower('".$data."')";
                if($array['type'] == 'edit'){
                        $select .= " and id !=".$data['vehicle_id'];
                }
                $result = pg_query($link, $select);
                if(pg_numrows($result) == 0){
                        return false;
                } else {
                        return true;
                }  
        }
}

?>