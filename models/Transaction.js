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
    amount: {
        type: Number,
        required: true
    },
    units: {
        type: String,
        required: true
    },
    charge: {
        type: Number,
        required: true
    },
    token: {
        type: String,
        required: true
    },
    location: {
        type: String,
        required: true
    },
    vtid: {
        type: String,
        required: true
    },
    ppid: {
        type: String,
        required: true
    },
    cost: {
        type: Number,
        required: true
    },
    tpl: {
        type: Number,
        required: true
    },
    commision: {
        type: Number,
        required: true
    },
    discountpercent: {
        type: Number,
        required: true
    },
    discountamount: {
        type: Number,
        required: true
    },
    tpm: {
        type: Number,
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



