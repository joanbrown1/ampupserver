const mongoose = require("mongoose");

const transactionSchema = new mongoose.Schema({
    email: {
        type: String,
        required: true
    },
    phone: {
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
    token: {
        type: String,
        required: true
    },
    amount: {
        type: String,
        required: true
    },
    vtpassid: {
        type: String,
        required: true
    },
    ppid: {
        type: String,
        required: true
    },
    units: {
        type: String,
        required: true
    },
    discountpercent: {
        type: String,
        required: true
    },
    discountcharge: {
        type: String,
        required: true
    },
    commision: {
        type: String,
        required: true
    },
    charge: {
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



