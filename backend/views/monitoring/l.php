<?php

header("Content-Type: text/json;charset=utf-8");

$students = [
    ['id'=>1,"name"=>"姓名1"],
    ['id'=>2,"name"=>"姓名2"],
    ['id'=>3,"name"=>"姓名3"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
    ['id'=>4,"name"=>"姓名4"],
];


echo json_encode($students);
