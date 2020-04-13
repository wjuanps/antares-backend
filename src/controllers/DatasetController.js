const Dataset = require("../models/Dataset");

module.exports = {
  async getTweets(sentiment) {
    let tweets = await Dataset.find({ sentiment });

    return tweets;
  },
};
