<?php

$app->get('/users', function($req, $res){
   $data = $this->db->query('select id, username, email, joined_at from users')->fetchAll();
   return $res->withJson($data);
});

$app->post('/user', function($req, $res){
   return $res->withJson(auth_user($req));
});

$app->get('/clean', function($req, $res){
   $this->db->exec('truncate table users');
});