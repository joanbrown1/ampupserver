const mongoose = require("mongoose");

const messageSchema = new mongoose.Schema({
    convo_id: {
        type: String,
        required: true
    },
    message: {
        type: String,
        required: true
    },
    sender_email: {
        type: String,
        required: true
    },
    time: {
        type: Date,
        default: Date.now,
        required: true
    },
});

const Messsage = mongoose.model("Message", messageSchema);

module.exports = { Messsage };



