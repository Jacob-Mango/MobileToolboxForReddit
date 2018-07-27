<?php

namespace RTPWA\API;

use RTPWA\API\Authenticator;

class RedditAPI
{

    public function __construct($app)
    {
        $this->scope = "creddits,modcontributors,modmail,modconfig,subscribe,structuredstyles,vote,wikiedit,mysubreddits,submit,modlog,modposts,modflair,save,modothers,read,privatemessages,report,identity,livemanage,account,modtraffic,wikiread,edit,modwiki,modself,history,flair";

        $this->authorizeUrl = 'https://ssl.reddit.com/api/v1/authorize';
        $this->accessTokenUrl = 'https://ssl.reddit.com/api/v1/access_token';
        $this->clientId = $app->config->get('reddit.client_id');
        $this->clientSecret = $app->config->get('reddit.secret');
        $this->userAgent = str_replace("{version}", "v0.0.2", $app->config->get('reddit.user_agent'));

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->redirectUrl = "http://192.168.0.53/";

        $this->client = null;

        $this->token_type = null;
        $this->access_token = null;
    }

    public function login() {
        $this->client = new \OAuth2\Client($this->clientId, $this->clientSecret, \OAuth2\Client::AUTH_TYPE_AUTHORIZATION_BASIC);
        $this->client->setCurlOption(CURLOPT_USERAGENT, $this->userAgent);

        if(isset($_COOKIE['reddit_token'])){
            $token_info = explode(":", $_COOKIE['reddit_token']); 

            $this->token_type = $token_info[0];
            $this->access_token = $token_info[1];
        } else { 
            if (!isset($_GET["code"])) {
                $authUrl = $this->client->getAuthenticationUrl($this->authorizeUrl, $this->redirectUrl, array(
                    "scope" => $this->scope,
                    "state" => "SomeUnguessableValue"
                ));
                
                header("Location: " . $authUrl);
                die("Redirect");
            } else {
                $code = $_GET["code"];
                
                $response = $this->client->getAccessToken($this->accessTokenUrl, \OAuth2\Client::GRANT_TYPE_AUTH_CODE, array(
                    "code" => $code,
                    "redirect_uri" =>  $this->redirectUrl,
                    "grant_type" => "authorization_code",
                    "scope" => $this->scope
                ))["result"];

                if (isset($response["access_token"])){
                    $this->access_token = $response["access_token"];
                    $this->token_type = $response["token_type"];

                    $cookie_time = time() + (60 * 60); 

                    setcookie('reddit_token', "{$this->token_type}:{$this->access_token}", $cookie_time); 
                } else {
                    echo '<pre>' . var_dump($response) . '</pre>';
                }
            }
        }
        
        $this->client->setAccessToken($this->access_token);
        $this->client->setAccessTokenType(\OAuth2\Client::ACCESS_TOKEN_BEARER);
    }

    public function remove($thing, $spam = false) {
        return $this->client->fetch("https://oauth.reddit.com/api/remove.json", array(
            "id" => $thing,
            "spam" => $spam
        ), \OAuth2\Client::HTTP_METHOD_POST);
    }

    public function comment($thing, $text) {
        return $this->client->fetch("https://oauth.reddit.com/api/comment.json", array(
            "thing_id" => $thing,
            "text" => $text
        ), \OAuth2\Client::HTTP_METHOD_POST);
    }
    
    public function sticky($thing, $sticky = true) {
        return $this->client->fetch("https://oauth.reddit.com/api/set_subreddit_sticky.json", array(
            "id" => $thing,
            "state" => $sticky
        ), \OAuth2\Client::HTTP_METHOD_POST);
    }

    private function strHasAt($string, $query, $index = 0) {
        return substr($string, $index, $index + strlen($query)) === $query;
    }

    public function link($url) {
        if ($this->strHasAt($url, "http://")) {
            $url = str_replace("http://", "https://", $url); 
        }

        if ($this->strHasAt($url, "https://reddit.com")) {
            $url = str_replace("reddit.com", "oauth.reddit.com", $url); 
        }

        if ($this->strHasAt($url, "https://www.reddit.com")) {
            $url = str_replace("www.reddit.com", "oauth.reddit.com", $url); 
        }

        if ($this->strHasAt($url, "https://m.reddit.com")) {
            $url = str_replace("m.reddit.com", "oauth.reddit.com", $url); 
        }
        
        return $this->client->fetch($url . '.json', array(), \OAuth2\Client::HTTP_METHOD_GET);
    }

    
    public function readWiki($subreddit, $page) {        
        return $this->client->fetch("https://oauth.reddit.com/r/${subreddit}/wiki/${page}.json", array(), \OAuth2\Client::HTTP_METHOD_GET);
    }
};