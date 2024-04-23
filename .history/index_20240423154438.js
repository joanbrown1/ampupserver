
const express = require("express");
const app = express();
const cors = require("cors");
const connection = require("./db");
const { Transaction } = require("./models/Transaction");
const userRoutes = require("./routes/Users");
const authUserRoutes = require("./routes/authUser");
const { User } = require("./models/user");

app.use(express.json());
app.use(express.urlencoded({ extended: false }));

// database connection
connection();

// middlewares
app.use(express.json());
app.use(cors()); // Enable CORS for all origins

// routes
app.use("/api/users", userRoutes);
app.use("/api/authUser", authUserRoutes);
app.get('/users', async(req, res) => {
    try {
        const users = await User.find({});
        res.status(200).json(users);
    } catch (error) {
        res.status(500).json({message: error.message})
    }
});

// API endpoint to search for users by email
app.post("/users/email", async (req, res) => {
  try {
    const searchemail = req.body.email;
    if (!searchemail) {
      return res.status(400).json({ message: "email parameter is missing" });
    }

    // Use a regular expression to perform a case-insensitive search for similar categories
    const regex = new RegExp(searchemail, "i");

    // Search for users directly in the database
    const users = await User.find({ email: regex }).exec();

    if (users.length === 0) {
      return res.status(404).json({ message: "No users found for the provided email" });
    }

    res.status(200).json(users);
  } catch (error) {
    console.error("Error searching for users:", error);
    res.status(500).json({ message: "Internal server error" });
  }
});

// API Endpoint to update meter number
app.put('/updatemeter', async (req, res) => {
  try {
      
      // Find user by email
      const { email, meternumber } = req.body;
      const user = await User.findOne({ email });
      if (!user) {
          return res.status(404).json({ message: 'User not found' });
      }

      // Update meter number
      user.meternumber = meternumber;
      await user.save();

      res.status(200).json({ message: 'Meter number updated successfully' });
  } catch (err) {
      console.error(err);
      res.status(500).json({ message: 'Internal server error' });
  }
});

app.post('/transaction', async (req, res) => {
    try {
        await Transaction.create(req.body);
        res.status(200).json({message: "Transaction created"});
    } catch (error) {
        console.error(error.message);
        res.status(500).json({ message: error.message });
    }
});

app.get('/transactions', async (req, res) => {
    try {
        const transactions = await Transaction.find({});

        // Sort transactions by timestamp in ascending order (earliest first)
        transactions.sort((a, b) => b.timestamp - a.timestamp);

        res.status(200).json(transactions);
    } catch (error) {
        console.error(error.message);
        res.status(500).json({ message: error.message });
    }
});

// API endpoint to search for transactions by email
app.post("/transactions/email", async (req, res) => {
    try {
      const searchemail = req.body.email;
      if (!searchemail) {
        return res.status(400).json({ message: "email parameter is missing" });
      }
  
      // Use a regular expression to perform a case-insensitive search for similar categories
      const regex = new RegExp(searchemail, "i");
  
      // Search for transactions directly in the database
      const transactions = await Transaction.find({ email: regex }).exec();
  
      if (transactions.length === 0) {
        return res.status(404).json({ message: "No transactions found for the provided email" });
      }

       // Sort transactions by timestamp in ascending order (earliest first)
       transactions.sort((a, b) => b.timestamp - a.timestamp);
  
      res.status(200).json(transactions);
    } catch (error) {
      console.error("Error searching for transactions:", error);
      res.status(500).json({ message: "Internal server error" });
    }
  });

//app.listen();

const PORT = process.env.PORT || 3030;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});
