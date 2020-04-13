const bayes = require("bayes");
const datasetController = require("./DatasetController");

const classifier = bayes();

module.exports = {
  async classifier(request, response) {
    let negatives = await datasetController.getTweets(0);
    let positives = await datasetController.getTweets(1);

    negatives.forEach(async (negatice) => {
      await classifier.learn(negatice.text, "negative");
    });

    positives.forEach(async (positive) => {
      await classifier.learn(positive.text, "positive");
    });

    const result = await classifier.categorize(
      "impressionante, legal, maravilhoso!! Yay."
    );

    // return response.json(bayes.fromJson(classifier.toJson()));
    return response.json({ result });
  },
};
