const mongoose = require("mongoose");

const chargeSchema = new mongoose.Schema({
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

const Charge = mongoose.model("Charge", chargeSchema);

module.exports = { Charge };



