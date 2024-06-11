const mongoose = require("mongoose");
const { v4: uuidv4 } = require('uuid');


const conversationSchema = new mongoose.Schema({
    convo_id: {
        type: String,
        default: uuidv4(),
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



