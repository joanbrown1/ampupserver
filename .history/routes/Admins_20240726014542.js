const router = require("express").Router();
// const {admin, validate} = require("../models/admin");
const {Admin} = require("../models/Admin");
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

router.post("/", async(req, res) => {
    try {
        // const {error} = validate(req.body);
        // if (error)
        //     return  res.status(400).send({message: error.details[0].message});

        const admin = await Admin.findOne({ email: req.body.email });
        if(admin)
            return   res.status(409).send({ message : "admin with given email already exists!"});

        const salt = await bcrypt.genSalt(Number(10));
        const hashPassword = await bcrypt.hash(req.body.password, salt);
        

        await new Admin({...req.body, password: hashPassword}).save();
        res.status(201).send({message: "admin created successfully"})

        const privilage = req.body.privilage

        const mailOptions = {
            from: 'no-reply@powerkiosk.ng',
            to: email, 
            subject: `You have been given ${privilage} rights`,
            html: `<p>You have been given ${privilage} rights. Password: admin12345 <br/> To change you password login and here <a href="https://admin1.powerkiosk.ng/">www.admin1.powerkiosk.ng</a></p>`
         };

          transporter.sendMail(mailOptions, (error, info) => {
            if (error) {
                console.error(error);
                res.status(500).json({ message: 'Email sending failed' });
              } else {
                console.log('Email sent: ' + info.response);
                res.status(200).json({ message: 'Email sent' });
              }
          });
    } catch (error) {
        // console.log('Email sent: ' + info.response);
        
        res.status(500).send({message: "Internal Server Error. Confirmation email sent."})
    }
})

module.exports = router;