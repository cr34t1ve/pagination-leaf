<?php

require __DIR__ . "/vendor/autoload.php";

$app = new Leaf\App();
$db = new Leaf\Db("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

$auth = new Leaf\Auth();
$auth->connect("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

// get
$app->get("/users", function () use($app, $db) {
    $users = $db->select("courtrulings")->limit(10)->all();

    $app->response()->json($users);
});

// get DB object
// $app->get("/users/(\d+)", function ($name) use($app, $db) {
//     $user = $db->select("usersDB")->where("name", $name)->first();

//     $app->response()->json($user);
// });

$app->run();
