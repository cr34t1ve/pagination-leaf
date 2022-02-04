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
$app->get("customlimit2", function () use($app, $db){
    $customlimit2 = $db->query('SELECT COUNT * FROM courtrulings WHERE id BETWEEN 1000 AND 2000')->count();
    $app->response()->json($customlimit2);
});

$app->get("customlimit3", function () use($app, $db){
    $customlimit3 = $db->query('SELECT * FROM courtrulings WHERE id BETWEEN 1000 AND 2000')->count();
    $app->response()->json($customlimit3);
});

//custom limit
$app->post("/customlimit", function() use($app, $db) {
    $start = $app->request()->get("start");
    $end = $app->request()->get("end");
    $users = $db->query("SELECT * FROM courtrulings WHERE id BETWEEN $start AND $end")->count();

    $app->response()->json($users);
  });
  
  $app->post("/customlimitt", function() use($app, $db) {
    $start = $app->request()->get("start");
    $users = $db->query("SELECT * FROM courtrulings")->limit($start)->all();

    $app->response()->json($users);
  });

//get ten rows
$app->get("/tenrows", function () use($app, $db) {
    $users = $db->select("courtrulings")->limit(10)->all();

    $app->response()->json($users);
});

//leaf query example
$app->get("queryexp", function () use($app, $db){
    $queryexp = $db->query('SELECT * FROM courtrulings')->where("date", "2011")->orderBy("id", "asc")->limit(3)->all();
    $app->response()->json($queryexp);
});

$app->get("/getken", function () use($app, $db) {
    $name = $app->request()->get("name");
    $getken = $db->select("usersDB")->where("name", $name)->first();

    $app->response()->json($getken);
});

// get
$app->get("/searchdb", function () use($app, $db) {
    $title = $app->request()->get("title");
    if($title){
     $searchdb = $db->select("courtrulings")->whereLike("title", "%$title%")->limit(50)->all();
    //  $searchdb = $db->select("courtrulings", "title")->whereLike("title", "%$title%")->all();
    } else {
        $searchdb = $db->select("courtrulings", "id, title, link, author, date, courttype, summary, presiding, casetype, subjectmatter, caseno, counsel1, counsel2, courtname, caseDate, subjectmattertitle")->limit(100)->all();
    }

    $app->response()->json($searchdb);
});

$app->get("/addsupreme", function () use($app, $db) {
    $title = $app->request()->get("title");
    if($title){
     $addsupreme = $db->select("courtrulings", "id, title, link, author, date, courttype, summary, presiding, casetype, subjectmatter, caseno, counsel1, counsel2, courtname, caseDate, subjectmattertitle")->whereLike(["courtname" => "high", "title" => "%$title%"])->limit(50)->all();
    } else {
        $addsupreme = $db->select("courtrulings", "id, title, link, author, date, courttype, summary, presiding, casetype, subjectmatter, caseno, counsel1, counsel2, courtname, caseDate, subjectmattertitle")->whereLike("courtname", "high")->limit(50)->all();
    }
    $app->response()->json($addsupreme);
});

// get DB object
// (\d+) -- NUMBERS
$app->get("/users/{name}", function ($name) use($app, $db) {
    $user = $db->select("supap_users")->where("username", $name)->fetchAssoc();

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
