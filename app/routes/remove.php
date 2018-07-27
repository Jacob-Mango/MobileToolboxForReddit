<?php

$app->post('/remove', function() use ($app) {
    $url = $app->request()->post('url');
    $preview = $app->request()->post('preview');

    echo 'URL: ' . $url;
    echo "<br>";
    echo "<br>";

    echo 'Preview: ' . $preview;
    echo "<br>";
    echo "<br>";

    $action_thing = "t3_91fvch";
    
    //$footer = $app->request()->post('footer');
    //$header = $app->request()->post('header');

    $reddit = $app->reddit;

    $reddit->login();
    
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
        $url = "https://oauth.reddit.com/r/Jacob_Mango/comments/91fvch/testicles_above_the_number_2/";
        
    $data = $reddit->link($url);

    echo key($data);

    echo "<pre>";
    print_r($data);
    echo "</pre>";


    $sub = "" ;//$data[0]->children[0]->data->subreddit;
    $type = true ? "comment" : "post";
    $user = "u/Jacob_Mango";

    $content = "N/A";

    $app->render('remove.php', array(
        'url' => $url,
        'reasons' => array(
            array(
                'index' => 0, 
                'header' => "Spam",
                'message' => "Your {type} was removed for spam."
            ),
            array(
                'index' => 1, 
                'header' => "Breaking rule 1",
                'message' => "Your {type} was removed for breaking rule 1."
            )
        ),
        'header' => "Hello {user}, your {type} was removed.",
        'footer' => "If you wish to dispute this, [send us a modmail](https://www.reddit.com/message/compose?to=%2Fr%2F{sub}).",
        'number_of_reasons' => 2,
        'content' => $content,
        'sub' => $sub,
        'type' => $type,
        'user' => $user
    ));
})->name('remove');