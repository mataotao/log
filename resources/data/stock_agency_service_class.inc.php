<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"stock_agency_service_class","way"=>"main")
                                );
$config["field"]=array(
                        "s"=>array(
                                //基本字段
                                array("name"=>"asc_id","db_field"=>"asc_id","format"=>""),
                                array("name"=>"asc_title","db_field"=>"asc_title","format"=>""),
                                array("name"=>"asc_description","db_field"=>"asc_description","format"=>""),
                                
                                //时间字段
                                array("name"=>"time_add","db_field"=>"asc_time","format"=>""),
                                array("name"=>"time_add_date","db_field"=>"asc_time","format"=>"dateFull"),
                                array("name"=>"time_update","db_field"=>"update_time","format"=>""),
                                array("name"=>"time_update_date","db_field"=>"update_time","format"=>"dateFull"),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"max_id","field"=>"asc_id","exist"=>"assign","type"=>">"),//id>0
                                array("name"=>"asc_id","field"=>"asc_id","exist"=>"assign","type"=>"="), 
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"asc_id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"asc_id","exist"=>"assign","type"=>"desc"),
);