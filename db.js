const mongoose = require("mongoose");

module.exports = async () => {
  const connectionParams = {
    useNewUrlParser: true,
    useUnifiedTopology: true,
  };

  try {
    await mongoose.connect("mongodb+srv://Joannabrown1:JoannaBrown1@cluster0.s5zxy47.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0", connectionParams);
    console.log("Connected to DB successfully");
  } catch (error) {
    console.error("Error connecting to DB:", error);
    throw error; // Rethrow the error to signify the failure to connect
  }
};
