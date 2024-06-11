const mongoose = require("mongoose");

const conversationSchema = new mongoose.Schema({
    convo_id: {
        type: String,
        required: true
    },
    sender_email: {
        type: String,
        required: true
    },
    receiver_email: {
        type: String,
        required: true
    },
});

const Conversation = mongoose.model("Conversation", conversationSchema);

module.exports = { Conversation };



