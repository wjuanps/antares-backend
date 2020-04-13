<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use HybridLogic\Classifier\Basic;
use HybridLogic\Classifier;
use App\Model\Dataset;

class ClassifierController extends Controller {

  public function __construct() {
    $this->classifier = new Classifier(new Basic);
    $this->dataset = new Dataset;
  }

  public function classify(String $text) {
    return $this->classifier->classify($text);
  }

  public function trainModel() {
    $negatives = $this->dataset->getTweetsTrain(0);
    $positives = $this->dataset->getTweetsTrain(1);

    $this->classifier->train("positive", $positives);
    $this->classifier->train("negative", $negatives);
  }
}
