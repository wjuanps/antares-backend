const express = require("express");
const DatasetController = require("./controllers/DatasetController");
const ClassifierController = require("./controllers/ClassifierController");

const routes = express.Router();

routes.get("/tweets", ClassifierController.classifier);

module.exports = routes;
