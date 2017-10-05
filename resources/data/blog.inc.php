<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"blog","way"=>"main")
                                );
$config["field"]=array(
                        "s"=>array(
                                //基本字段
                                array("name"=>"blog_id","db_field"=>"blog_id","format"=>""),
                                array("name"=>"title","db_field"=>"title","format"=>""),
                                array("name"=>"name","db_field"=>"name","format"=>""),
                                
                                
                                //时间字段
                                array("name"=>"time_add","db_field"=>"time_add","format"=>""),
                                array("name"=>"time_add_date","db_field"=>"time_add","format"=>"dateFull"),
                                array("name"=>"time_update","db_field"=>"time_update","format"=>""),
                                array("name"=>"time_update_date","db_field"=>"time_update","format"=>"dateFull"),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"max_id","field"=>"blog_id","exist"=>"assign","type"=>">"),//id>0
                                array("name"=>"blog_id","field"=>"blog_id","exist"=>"assign","type"=>"="), 
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"blog_id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"blog_id","exist"=>"assign","type"=>"desc"),
);