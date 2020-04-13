const mongoose = require("mongoose");

const DatasetSchema = new mongoose.Schema({
  twitter_id: Number,
  sentiment: Number,
  user: String,
  text: String,
  posted_at: String,
});

module.exports = mongoose.model("Dataset", DatasetSchema);
