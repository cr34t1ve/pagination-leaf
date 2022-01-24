<?php

require __DIR__ . "/vendor/autoload.php";

$app = new Leaf\App();
$db = new Leaf\Db("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

$auth = new Leaf\Auth();
$auth->connect("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

// get
$app->get("/tenobj", function () use($app, $db) {
    $users = $db->select("courtrulings")->limit(10)->all();

    $app->response()->json($users);
});

//leaf query example
$app->get("queryexp", function () use($app, $db){
    $users = $db->query('SELECT 10 FROM courtrulings');
});

$app->get("customlimit2", function () use($app, $db){
    $users = $db->query('SELECT * FROM courtrulings WHERE id BETWEEN 10 AND 20')->all();
});

// set between start and end
$app->post("/customlimit", function() use($app, $db) {
    $start = $app->request()->get("start");
    $end = $app->request()->get("end");
    $users = $db->query("SELECT * FROM courtrulings WHERE id BETWEEN $start AND $end")->count();

    $app->response()->json($users);
  });

  $app->get("/rulings/(\d+)", function ($date) use($app, $db) {
    $roww = $db->select("courtrulings")->where("date", $date)->first();

    $app->response()->json($roww);
});

$app->get("/getken", function ($name) use($app, $db) {
    $name = $app->request()->get("name");
    $getken = $db->select("usersDB")->where("name", $name)->all();

    $app->response()->json($getken);
});

// get DB object
// $app->get("/users/(\d+)", function ($name) use($app, $db) {
//     $user = $db->select("usersDB")->where("name", $name)->first();

//     $app->response()->json($user);
// });

$app->run();
