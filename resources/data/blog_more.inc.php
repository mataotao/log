<?php
$config = array();
$config["connection_mode"]=array(
                                    "s"=>array("table_name"=>"bbs","way"=>"main"),
                                    "c"=>array("table_name"=>"user","way"=>"left_join","condition"=>"s.user_id=c.user_id "),
                                );
$config["field"]=array(
                        "s"=>array(
                                array("name"=>"bbs_id","db_field"=>"bbs_id","format"=>""),

                                array("name"=>"user_id","db_field"=>"user_id","format"=>""),
                                array("name"=>"author","db_field"=>"author","format"=>""),
                                array("name"=>"title","db_field"=>"title","format"=>""),
                                array("name"=>"img_url","db_field"=>"img_url","format"=>""),
                                array("name"=>"content","db_field"=>"content","format"=>""),
                                array("name"=>"num_read","db_field"=>"num_read","format"=>""),
                                array("name"=>"num_comment","db_field"=>"num_comment","format"=>""),

                                //时间字段
                                array("name"=>"time_add","db_field"=>"time_add","format"=>""),
                                array("name"=>"time_add_date","db_field"=>"time_add","format"=>"date_simple"),
                                array("name"=>"time_update","db_field"=>"time_update","format"=>""),
                                array("name"=>"time_update_date","db_field"=>"time_update","format"=>"date_full"),
                            ),
                        'c'=>array(
                                array("name"=>"nike_name","db_field"=>"nike_name","format"=>""),
                                array("name"=>"user_name","db_field"=>"user_name","format"=>""),
                                array("name"=>"avatar","db_field"=>"avatar","format"=>""),
                            )
                        );
$config["condition"]=array(
                            "s"=>array(
                                //基本字段
                                array("name"=>"bbs_id","field"=>"bbs_id","exist"=>"assign","type"=>"="),
                                array("name"=>"max_id","field"=>"bbs_id","exist"=>"assign","type"=>">"),

                                array("name"=>"user_id","field"=>"user_id","exist"=>"assign","type"=>"like"),
                                array("name"=>"title","field"=>"title","exist"=>"assign","type"=>"like"),
                            ),
                            "c"=>array(
                                //基本字段
                                array("name"=>"max_id_more","field"=>"user_id","exist"=>"assign","type"=>">"),
                            )
                       );
$config["sort"]=array(
    //时间排序
    array("table"=>"s","name"=>"id_asc","field"=>"bbs_id","exist"=>"assign","type"=>"asc"),
    array("table"=>"s","name"=>"id_desc","field"=>"bbs_id","exist"=>"assign","type"=>"desc"),
);
