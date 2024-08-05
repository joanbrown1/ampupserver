const router = require("express").Router();
const { Admin } = require("../models/Admin");
const bcrypt = require("bcrypt");
const nodemailer = require('nodemailer');

// Configure nodemailer with your email settings
const transporter = nodemailer.createTransport({
  host: 'mail.powerkiosk.ng',
  port: 465,
  secure: true, // true for 465, false for other ports
  auth: {
    user: 'no-reply@powerkiosk.ng',
    pass: '6Nwj2OLxo9lh8DZ'
  },
  debug: true, // Enable debug output
  logger: true // Enable logging
});

router.post("/", async (req, res) => {
  try {
    // const {error} = validate(req.body);
    // if (error)
    //     return  res.status(400).send({message: error.details[0].message});

    const admin = await Admin.findOne({ email: req.body.email });
    if (admin)
      return res.status(409).send({ message: "admin with given email already exists!" });

    const salt = await bcrypt.genSalt(Number(10));
    const hashPassword = await bcrypt.hash(req.body.password, salt);

    await new Admin({ ...req.body, password: hashPassword }).save();

    const privilage = req.body.privilage;
    let link;

    if (privilage === "admin") {
      link = "https://admin1.powerkiosk.ng/";
    } else {
      link = "https://custcare.powerkiosk.ng/";
    }

    const mailOptions = {
      from: 'no-reply@powerkiosk.ng',
      to: req.body.email, 
      subject: `You have been given ${privilage} rights`,
      html: `<p>You have been given ${privilage} rights. Password: admin12345 <br/> To change your password, login here: <a href=${link}>${link}</a></p>`
    };

    transporter.sendMail(mailOptions, (error, info) => {
      if (error) {
        console.error(error);
        return res.status(201).json({ message: 'Admin created, but email sending failed' });
      } else {
        console.log('Email sent: ' + info.response);
        res.status(200).json({ message: 'Admin created successfully and email sent' });
      }
    });
  } catch (error) {
    console.error(error);
    res.status(500).send({ message: "Internal Server Error" });
  }
});

module.exports = router;
