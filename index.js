
const express = require("express");
const app = express();
const cors = require("cors");
const connection = require("./db");
const { Transaction } = require("./models/Transaction");
const { Charge } = require("./models/Charge");
const { Discount } = require("./models/Discount");
const { Admin } = require("./models/Admin");
const userRoutes = require("./routes/Users");
const authUserRoutes = require("./routes/authUser");
const updateUserRoutes = require("./routes/updateUser");
const updateAdminRoutes = require("./routes/updateAdmin");
const { User } = require("./models/user");
const exceljs = require('exceljs');

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
app.use("/update/user", updateUserRoutes);
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

// New API endpoint to download user data in Excel format
app.get('/download/users', async (req, res) => {
  try {
      const users = await User.find({});

      // Create a new workbook
      const workbook = new exceljs.Workbook();
      const worksheet = workbook.addWorksheet('Users');

      // Define column headers
      worksheet.columns = [
          { header: 'Email', key: 'email', width: 30 },
          { header: 'Phone Number', key: 'phonenumber', width: 30 },
          { header: 'Meter Number', key: 'meternumber', width: 30 },
          { header: 'Password', key: 'password', width: 30 },
      ];

      // Add data rows
      users.forEach(user => {
          worksheet.addRow({
              email: user.email,
              phonenumber: user.phonenumber,
              meternumber: user.meternumber,
              password: user.password,
          });
      });

      // Set response headers for file download
      res.setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
      res.setHeader('Content-Disposition', 'attachment; filename=' + 'users.xlsx');

      // Write workbook to response
      await workbook.xlsx.write(res);
      res.end();

  } catch (error) {
      console.error(error.message);
      res.status(500).json({ message: error.message });
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
        transactions.reverse();

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
       transactions.reverse();
  
      res.status(200).json(transactions);
    } catch (error) {
      console.error("Error searching for transactions:", error);
      res.status(500).json({ message: "Internal server error" });
    }
  });

  // API endpoint for charges
app.post('/charge', async (req, res) => {
    try {
        await Charge.create(req.body);
        res.status(200).json({message: "Charges added"});
    } catch (error) {
        console.error(error.message);
        res.status(500).json({ message: error.message });
    }
});

app.get('/charges', async (req, res) => {
    try {
        const charges = await Charge.find({});

        // Sort charges by timestamp in ascending order (earliest first)
        charges.reverse();

        res.status(200).json(charges);
    } catch (error) {
        console.error(error.message);
        res.status(500).json({ message: error.message });
    }
});

//API for Discount

app.post('/discount', async (req, res) => {
  try {
      await Discount.create(req.body);
      res.status(200).json({message: "Discount created"});
  } catch (error) {
      console.error(error.message);
      res.status(500).json({ message: error.message });
  }
});

app.get('/discounts', async (req, res) => {
  try {
      const discounts = await Discount.find({});

      // Sort discounts by timestamp in ascending order (earliest first)
      discounts.reverse();

      res.status(200).json(discounts);
  } catch (error) {
      console.error(error.message);
      res.status(500).json({ message: error.message });
  }
});

// API endpoint to search for discounts by code
app.post("/discounts/code", async (req, res) => {
  try {
    const searchcode = req.body.code;
    if (!searchcode) {
      return res.status(400).json({ message: "code parameter is missing" });
    }

    // Use a regular expression to perform a case-insensitive search for similar categories
    const regex = new RegExp(searchcode, "i");

    // Search for discounts directly in the database
    const discounts = await Discount.find({ code: regex }).exec();

    if (discounts.length === 0) {
      return res.status(404).json({ message: "No discounts found for the provided code" });
    }

     // Sort transactions by timestamp in ascending order (earliest first)
     discounts.reverse();

    res.status(200).json(discounts);
  } catch (error) {
    console.error("Error searching for discounts:", error);
    res.status(500).json({ message: "Internal server error" });
  }
});

// API for Admin

app.post('/admin', async (req, res) => {
  try {
      await Admin.create(req.body);
      res.status(200).json({message: "Admin created"});
  } catch (error) {
      console.error(error.message);
      res.status(500).json({ message: error.message });
  }
});

app.get('/admins', async (req, res) => {
  try {
      const admins = await Admin.find({});

      // Sort admins by timestamp in ascending order (earliest first)
      admins.reverse();

      res.status(200).json(admins);
  } catch (error) {
      console.error(error.message);
      res.status(500).json({ message: error.message });
  }
});

app.use("/update/admin", updateAdminRoutes);

//app.listen();

const PORT = process.env.PORT || 3030;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});

