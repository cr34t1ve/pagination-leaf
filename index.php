<?php

require __DIR__ . "/vendor/autoload.php";

$app = new Leaf\App();
$db = new Leaf\Db("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

$auth = new Leaf\Auth();
$auth->connect("localhost", "id17890759_usersdb", "Boondocks-125", "id17890759_users");

// auth
$app->post("/login", function () use ($app, $auth) {
    $data = $app->request()->get(["username", "password"]);
    $user = $auth->login("supap_users", $data);

    if (!$user) {
        $app->response()->throwErr($auth->errors());
    }

    $app->response()->json($user);
});

// get
$app->get("/users", function () use($app, $db) {
    $users = $db->select("courtrulings")->all();

    $app->response()->json($users);
});

// get DB object
$app->get("/users/(\d+)", function ($name) use($app, $db) {
    $user = $db->select("usersDB")->where("name", $name)->first();

    $app->response()->json($user);
});

// html ftp
$app->get("/form", function () use($app) {
    echo "<form enctype='multipart/form-data' action='/upload' method='POST'><input type='file' name='file'><button type='submit'>Submit</button></form>";
});

// upload
$app->post("/upload", function () use($app) {
    $file = $app->request()->files("file");
    Leaf\FS::uploadFile($file, "e-judgment/links/cases/");
});

$app->run();
