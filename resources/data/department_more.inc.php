<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"stock_department","way"=>"main"),
                                    "c"=>array("table_name"=>"stock_company","way"=>"left_join","condition"=>"s.company_id=c.company_id"),
                                );
$config["field"]=array(
                        "s"=>array(
                                array("name"=>"id","db_field"=>"id","format"=>""),

                                array("name"=>"name","db_field"=>"name","format"=>""),
                                array("name"=>"principal_mobile","db_field"=>"principal_mobile","format"=>""),
                                array("name"=>"principal","db_field"=>"principal","format"=>""),
                                array("name"=>"agency_id","db_field"=>"agency_id","format"=>""),
                                array("name"=>"status","db_field"=>"status","format"=>""),
                                array("name"=>"company_id","db_field"=>"company_id","format"=>""),

                                //时间字段
                                array("name"=>"created_at","db_field"=>"created_at","format"=>""),
                                array("name"=>"updated_at","db_field"=>"updated_at","format"=>""),
                            ),
                        'c'=>array(
                                array("name"=>"prefix","db_field"=>"prefix","format"=>""),
                                array("name"=>"company_name","db_field"=>"name","format"=>""),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"id","field"=>"id","exist"=>"assign","type"=>"="),
                                array("name"=>"max_id","field"=>"id","exist"=>"assign","type"=>">"),

                                array("name"=>"name","field"=>"name","exist"=>"assign","type"=>"like"),
                                array("name"=>"principal","field"=>"principal","exist"=>"assign","type"=>"like"),
                                array("name"=>"principal_mobile","field"=>"principal_mobile","exist"=>"assign","type"=>"like"),
                                array("name"=>"company_id","field"=>"company_id","exist"=>"assign","type"=>"="),
                                array("name"=>"status","field"=>"status","exist"=>"assign","type"=>"="),
                                array("name"=>"agency_id","field"=>"agency_id","exist"=>"assign","type"=>"="),

                                array("name"=>"time_start","field"=>"created_at","exist"=>"assign","type"=>">="),
                                array("name"=>"time_end","field"=>"created_at","exist"=>"assign","type"=>"<="),
                            ),
                            "c"=>array(
                                //基本字段
                                array("name"=>"max_id_more","field"=>"id","exist"=>"assign","type"=>">"),
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"id","exist"=>"assign","type"=>"desc"),
);
