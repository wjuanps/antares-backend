<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Model\Twitter;

class SentimentController extends Controller {

  public function __construct() {
    $this->classifierController = new ClassifierController;
  }

  public function index(Request $request, Twitter $twitter) {
    $this->classifierController->trainModel();

    $tweets = $this->getTweets($request->q, $twitter);
    $result = $this->getResult($tweets);

    return response()
      ->json($result);
  }

  protected function getTweets(String $q, Twitter $twitter) {
    $tweets = $twitter->search($q, 100);
    return array_map(function ($tweet) {
      return (
        (object) array(
          "id"           => $tweet->id_str,
          "userName"     => $tweet->user->name,
          "userProfile"  => $tweet->user->screen_name,
          "profileImage" => $tweet->user->profile_image_url_https,
          "text"         => $this->removeLink($tweet->text),
          "posted_at"    => $tweet->created_at,
          "groups"       => $this->classifierController
            ->classify($this->removeLink($tweet->text))
        )
      );
    }, $tweets->statuses);
  }

  protected function getResult($tweets) {
    $positive = $this->getPolarity($tweets, "positive");
    $negative = $this->getPolarity($tweets, "negative");

    $percentPositive = $this->getPercent($positive);
    $percentNegative = $this->getPercent($negative);

    $sentiment = $this->getSentiment($positive, $negative);

    shuffle($tweets);

    return array(
      "total"  => count($tweets),
      "tweets" => array_slice($tweets, 0, 5),
      "result" => array(
        "sentiment"       => $sentiment,
        "positive"        => $positive,
        "negative"        => $negative,
        "percentPositive" => $percentPositive,
        "percentNegative" => $percentNegative
      )
    );
  }

  protected function getPolarity(array $tweets, String $sentiment) : float {
    $temp = array_reduce($tweets, function ($carry, $item) use ($sentiment) {
      return $carry += $item->groups[$sentiment];
    });

    return ($temp / count($tweets));
  }

  protected function getSentiment(float $positive, float $negative) : int {
    return ($positive > $negative) ? 1 : 0;
  }

  protected function getPercent($value) {
    return $value * 100;
  }

  protected function removeLink($text) {
    return preg_replace(
      '#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#',
      '',
      $text
    );
  }
}
