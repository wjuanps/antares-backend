<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Dataset extends Model {

  public function getTweetsTrain(int $sentiment) {
    $tweets = $this->where("sentiment", $sentiment)->get();

    return array_map(function ($tweet) {
      return $tweet->text;
    }, $tweets->all());
  }
}
