const mongoose = require("mongoose");

const transactionSchema = new mongoose.Schema({
    email: {
        type: String,
        required: true
    },
    meternumber: {
        type: String,
        required: true
    },
    location: {
        type: String,
        required: true
    },
    amount: {
        type: String,
        required: true
    },
    date: {
        type: Date,
        default: Date.now,
        required: true
    },
});

const Transaction = mongoose.model("Transaction", transactionSchema);

module.exports = { Transaction };



