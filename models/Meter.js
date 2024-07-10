const mongoose = require("mongoose");

const meterSchema = new mongoose.Schema({
    meter: {
        type: Number,
        required: true
    },
    email: {
        email: String,
        required: true
    },
});

const Meter = mongoose.model("Meter", meterSchema);

module.exports = { Meter };



