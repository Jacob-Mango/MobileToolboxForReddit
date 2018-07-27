<?php

namespace RTPWA\API;

use RTPWA\API\Authenticator;

class RedditAPI
{

    public function __construct()
    {
        $this->scope = "creddits,modcontributors,modmail,modconfig,subscribe,structuredstyles,vote,wikiedit,mysubreddits,submit,modlog,modposts,modflair,save,modothers,read,privatemessages,report,identity,livemanage,account,modtraffic,wikiread,edit,modwiki,modself,history,flair";

        $this->authorizeUrl = 'https://ssl.reddit.com/api/v1/authorize';
        $this->accessTokenUrl = 'https://ssl.reddit.com/api/v1/access_token';
        $this->clientId = '9GY1r8pRgR0Szg';
        $this->clientSecret = '2YeC74zd4jiQulVYNBvMxfWQfWg';
        $this->userAgent = 'web:co.jacobmango.rtpwa:v0.0.1 (by /u/Jacob_Mango)';

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $this->redirectUrl = "http://127.0.0.1/";

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

    public function link($url) {
       // $this->guzzleClient = new \GuzzleHttp\Client(array(
       //     'headers' => ['User-Agent' => $this->userAgent],
       //     'scope' => $this->scope,
       //     'verify' => false
       // ));

        //return $this->guzzleClient->request("GET", $url . '.json');
        
        return $this->client->fetch($url . '.json', array(), \OAuth2\Client::HTTP_METHOD_GET);
    }

    
};