<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"stock_company_role","way"=>"main")
                                );
$config["field"]=array(
                        "s"=>array(
                                //基本字段
                                array("name"=>"id","db_field"=>"id","format"=>""),
                                array("name"=>"company_id","db_field"=>"company_id","format"=>""),
                                array("name"=>"role_id","db_field"=>"role_id","format"=>""),
                                
                                
                                //时间字段
                                array("name"=>"created_at","db_field"=>"created_at","format"=>""),
                                array("name"=>"updated_at","db_field"=>"updated_at","format"=>""),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"max_id","field"=>"id","exist"=>"assign","type"=>">"),//id>0
                                array("name"=>"id","field"=>"id","exist"=>"assign","type"=>"="), 
                                array("name"=>"role_id","field"=>"role_id","exist"=>"assign","type"=>"="), 
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"id","exist"=>"assign","type"=>"desc"),
);