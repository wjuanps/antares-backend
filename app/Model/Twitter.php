<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

use Abraham\TwitterOAuth\TwitterOAuth;

class Twitter extends Model {

  public function __construct() {
    $this->twitterOAuth = new TwitterOAuth(
      env("CONSUMER_KEY"),
      env("CONSUMER_SECRET"),
      env("ACCESS_TOKEN"),
      env("ACCESS_TOKEN_SECRET")
    );

    $this->dataset = new Dataset;
  }

  public function search($q, $count = 100, $resultType = "mixed", $lang = "pt") {
    try {
      $query = $this->getQueryString($q, $count, $resultType, $lang);

      $tweets = $this->twitterOAuth->get("search/tweets", $query);

      return $tweets;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  protected function getQueryString($q, $count, $resultType, $lang) {
    return array(
      "q"           => $q,
      "count"       => $count,
      "result_type" => $resultType,
      "lang"        => $lang,
    );
  }
}
