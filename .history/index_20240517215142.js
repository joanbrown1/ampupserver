
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
const puppeteer = require('puppeteer');

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

// API endpoint to search for transactions by ppid
app.post("/transactions/ppid", async (req, res) => {
    try {
      const searchppid = req.body.ppid;
      if (!searchppid) {
        return res.status(400).json({ message: "ppid parameter is missing" });
      }
  
      // Use a regular expression to perform a case-insensitive search for similar categories
      const regex = new RegExp(searchppid, "i");
  
      // Search for transactions directly in the database
      const transactions = await Transaction.find({ ppid: regex }).exec();
  
      if (transactions.length === 0) {
        return res.status(404).json({ message: "No transactions found for the provided ppid" });
      }

       // Sort transactions by timestamp in ascending order (earliest first)
       transactions.reverse();
  
      res.status(200).json(transactions);
    } catch (error) {
      console.error("Error searching for transactions:", error);
      res.status(500).json({ message: "Internal server error" });
    }
});

// API endpoint to search for transactions receipt by ppid
app.get("/transactions/:ppid", async (req,res) =>{

  const { ppid } = req.params;  

  try {

    // Find transaction by PPID in the database
    const transaction = await Transaction.findOne({ ppid });

    if (!transaction) {
        return res.status(404).send('Transaction not found');
    }

    const htmlContent = `
        <!-- HTML content remains the same -->

        <div style='text-align: center; color: #808080;'>
        <img src="https://coaching.bloomx.live/assets/ppp.png"/>
        <p style="font-size: 1.25rem; font-weight: bold; margin-top: 2.5rem; color: black;">Transaction Details</p>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Email:</p>
            <p style='margin-left: auto; color: black;'>${transaction.email}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Meter Number:</p>
            <p style='margin-left: auto; color: black;'>${transaction.meternumber}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Reference Number:</p>
            <p style='margin-left: auto; color: black;'>${transaction.ppid}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Paid At:</p>
            <p style='margin-left: auto; color: black;'>${transaction.date}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Location:</p>
            <p style='margin-left: auto; color: black;'>${transaction.location}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Token:</p>
            <p style='margin-left: auto; color: black;'>${transaction.token}</p>
        </div>
        <hr />
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Service Charge:</p>
            <p style='margin-left: auto; color: black;'>₦${transaction.charge}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Discount Amount:</p>
            <p style='margin-left: auto; color: black;'>₦${transaction.discountamount}</p>
        </div>
        <div style='text-align: center; color: #808080; margin:6px 100px; display: flex; justify-content: space-between;'>
            <p>Discount Percentage:</p>
            <p style='margin-left: auto; color: black;'>${transaction.discountpercent}%</p>
        </div>
        <div>
            <p style='text-align: center; font-size: 1.25rem; font-weight: bold; margin-top: 0.5rem;'>Total Amount Paid:</p>
            <p style='color: #7B0323; text-align: center; font-size: 1.25rem; padding-bottom: 2.5rem; font-weight: bold; font-style: italic;'>₦${transaction.amount}</p>
        </div>
    </div>

    `;

    const browser = await puppeteer.launch();
    const page = await browser.newPage();
    await page.setContent(htmlContent);
    const pdfBuffer = await page.pdf();
    await browser.close();
    
    res.contentType("application/pdf");
    res.send(pdfBuffer);
} catch (error) {
    console.error('Error generating PDF:', error);
    res.status(500).send('Error generating PDF');
}

});



//API transaction by date
app.post("/transactions/date", async (req, res) => {
  try {
    const dateParam = req.body.date;
    if (!dateParam) {
      return res.status(400).json({ message: "date parameter is missing" });
    }

    let startDate, endDate;

    // Parse the date parameter into a Date object
    const searchDate = new Date(dateParam);

    // Extract the year, month, and date from the search date
    const searchYear = searchDate.getUTCFullYear();
    const searchMonth = searchDate.getUTCMonth();
    const searchDay = searchDate.getUTCDate();

    // Calculate the start and end dates for the search (if searching by month or year)
    if (searchMonth !== undefined && searchYear !== undefined) {
      startDate = new Date(Date.UTC(searchYear, searchMonth, 1, 0, 0, 0));
      endDate = new Date(Date.UTC(searchYear, searchMonth + 1, 0, 23, 59, 59));
    } else if (searchYear !== undefined) {
      startDate = new Date(Date.UTC(searchYear, 0, 1, 0, 0, 0));
      endDate = new Date(Date.UTC(searchYear, 11, 31, 23, 59, 59));
    } else {
      startDate = new Date(Date.UTC(searchYear, searchMonth, searchDay, 0, 0, 0));
      endDate = new Date(Date.UTC(searchYear, searchMonth, searchDay, 23, 59, 59));
    }

    // Search for transactions in the database within the specified date range
    const transactions = await Transaction.find({
      date: { $gte: startDate, $lte: endDate }
    }).exec();

    if (transactions.length === 0) {
      return res.status(404).json({ message: "No transactions found for the provided date" });
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

// API endpoint to search for admins by email
app.post("/admins/email", async (req, res) => {
  try {
    const searchemail = req.body.email;
    if (!searchemail) {
      return res.status(400).json({ message: "email parameter is missing" });
    }

    // Use a regular expression to perform a case-insensitive search for similar categories
    const regex = new RegExp(searchemail, "i");

    // Search for users directly in the database
    const admins = await Admin.find({ email: regex }).exec();

    if (admins.length === 0) {
      return res.status(404).json({ message: "No admins found for the provided email" });
    }

    res.status(200).json(admins);
  } catch (error) {
    console.error("Error searching for users:", error);
    res.status(500).json({ message: "Internal server error" });
  }
});

app.use("/update/admin", updateAdminRoutes);

//app.listen();

const PORT = process.env.PORT || 3030;
app.listen(PORT, () => {
  console.log(`Server is running on port ${PORT}`);
});

