<?php

$app->post('/remove', function() use ($app) {
    $reddit = $app->reddit;
    $reddit->login();

    $url = $app->request()->post('url');
    $preview = $app->request()->post('preview');

    echo 'URL: ' . $url;
    echo "<br>";
    echo "<br>";

    echo 'Preview: ' . $preview;
    echo "<br>";
    echo "<br>";

    $data = $reddit->link($url);

    $action_thing = $data["result"][0]["data"]["children"][0]["data"]["name"];
    

    
    //$remove = $reddit->remove($action_thing, false);
    $comment = $reddit->comment($action_thing, $preview);
    //$sticky = $reddit->sticky($action_thing);



    echo '<pre>';
    print_r($comment);
    echo '</pre>';

    // $app->response->redirect($app->urlFor('home'));
})->name('remove.post');

$app->get('/remove', function() use ($app) {
    $url = $app->request()->get('url');

    $reddit = $app->reddit;

    $reddit->login();

    if ($url == null)
        $url = "http://reddit.com/r/Jacob_Mango/comments/91fvch/testicles_above_the_number_2/";
        
    $data = $reddit->link($url);

    $sub = $data["result"][0]["data"]["children"][0]["data"]["subreddit"];
    $type = $data["result"][0]["data"]["children"][0]["kind"] == "t3" ? "post" : "comment";
    $user = "/u/" . $data["result"][0]["data"]["children"][0]["data"]["author"];

    $toolboxWiki = json_decode($reddit->readWiki($sub, "toolbox")["result"]["data"]["content_md"]);
    $removal_reasons = $toolboxWiki->removalReasons->reasons;

    $header = urldecode($toolboxWiki->removalReasons->header);
    $footer = urldecode($toolboxWiki->removalReasons->footer);

    $reasons = array();

    for ($i = 0; $i < count($removal_reasons); $i++) 
    {
        array_push($reasons, array(
            'index' => $i, 
            'header' => $removal_reasons[$i]->title,
            'message' => $removal_reasons[$i]->text
        ));
    }

    $content = "N/A";

    $app->render('remove.php', array(
        'url' => $url,
        'reasons' =>  $reasons,
        'header' => $header,
        'footer' => $footer,
        'number_of_reasons' => count($reasons),
        'sub' => $sub,
        'type' => $type,
        'user' => $user
    ));
})->name('remove');