const mongoose = require("mongoose");

const discountSchema = new mongoose.Schema({
    code: {
        type: String,
        required: true
    },
    percent: {
        type: Number,
        required: true
    },
    amount: {
        type: Number,
        required: true
    },
    limit: {
        type: Date,
        required: true
    },
});

const Discount = mongoose.model("Discount", discountSchema);

module.exports = { Discount };



