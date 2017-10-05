<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"sys_roles","way"=>"main")
                                );
$config["field"]=array(
                        "s"=>array(
                                //基本字段
                                array("name"=>"id","db_field"=>"id","format"=>""),
                                array("name"=>"name","db_field"=>"name","format"=>""),
                                
                                
                                //时间字段
                                array("name"=>"time_add","db_field"=>"created_at","format"=>""),
                                array("name"=>"time_add_date","db_field"=>"created_at","format"=>"dateFull"),
                                array("name"=>"time_update","db_field"=>"updated_at","format"=>""),
                                array("name"=>"time_update_date","db_field"=>"updated_at","format"=>"dateFull"),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"max_id","field"=>"id","exist"=>"assign","type"=>">"),//id>0
                                array("name"=>"id","field"=>"id","exist"=>"assign","type"=>"="), 
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"id","exist"=>"assign","type"=>"desc"),
);