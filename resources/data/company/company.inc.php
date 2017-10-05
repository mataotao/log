<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"stock_company","way"=>"main")
                                );
$config["field"]=array(
                        "s"=>array(
                                //基本字段
                                array("name"=>"company_id","db_field"=>"company_id","format"=>""),
                                array("name"=>"prefix","db_field"=>"prefix","format"=>""),
                                array("name"=>"name","db_field"=>"name","format"=>""),
                                array("name"=>"agency_id","db_field"=>"agency_id","format"=>""),
                                
                                
                                //时间字段
                                array("name"=>"created_at","db_field"=>"created_at","format"=>""),
                                array("name"=>"updated_at","db_field"=>"updated_at","format"=>""),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"max_id","field"=>"company_id","exist"=>"assign","type"=>">"),//id>0
                                array("name"=>"company_id","field"=>"company_id","exist"=>"assign","type"=>"="), 
                                array("name"=>"agency_id","field"=>"agency_id","exist"=>"assign","type"=>"="), 
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"company_id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"company_id","exist"=>"assign","type"=>"desc"),
);