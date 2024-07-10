const mongoose = require("mongoose");

const chargeSchema = new mongoose.Schema({
    meter: {
        type: Number,
        required: true
    },
    email: {
        email: String,
        required: true
    },
});

const Charge = mongoose.model("Charge", chargeSchema);

module.exports = { Charge };



