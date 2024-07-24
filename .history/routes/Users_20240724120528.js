const router = require("express").Router();
// const {User, validate} = require("../models/user");
const {User} = require("../models/user");
const bcrypt = require("bcrypt");
const nodemailer = require('nodemailer');

// Configure nodemailer with your email settings
const transporter = nodemailer.createTransport({
    host: 'mail.powerkiosk.ng',
    port: 465,
    secure: true, // true for 465, false for other ports
    auth: {
      user: 'support@powerkiosk.ng',
      pass: 'WCckgFiiWy1faVx'
    },
    debug: true, // Enable debug output
    logger: true // Enable logging
  });

router.post("/", async(req, res) => {
    try {
        // const {error} = validate(req.body);
        // if (error)
        //     return  res.status(400).send({message: error.details[0].message});

        const user = await User.findOne({ email: req.body.email });
        if(user)
            return   res.status(409).send({ message : "User with given email already exists!"});

        const salt = await bcrypt.genSalt(Number(10));
        const hashPassword = await bcrypt.hash(req.body.password, salt);
        

        await new User({...req.body, password: hashPassword}).save();
        res.status(201).send({message: "User created successfully"});


        const mailOptions = {
            from: 'support@powerkiosk.ng',
            to: req.body.email,
            subject: 'Welcome to Powerkiosk.ng!',
            html: `<p>Dear ${res.body.name},</p>
            <br/><p>Thank you for registering with Powerkiosk.ng! We are delighted to have you as part of our community and are excited to serve your electricity vending needs.</p>
            <br/><p>At Powerkiosk.ng, we aim to make electricity vending seamless, efficient, and accessible for all. Our platform provides a comprehensive range of services designed to simplify your experience, including:</p>
            <br/><p>•	Easy and Quick Payments: Purchase your electricity tokens effortlessly through our user-friendly interface.</p>
            <br/><p>•	24/7 Service: Access our services anytime, anywhere, ensuring you never experience downtime.</p>
            <br/><p>•	Secure Transactions: Your security is our priority. We ensure all transactions are safe and reliable.</p>
            <br/><p>•	Customer Support: Our dedicated support team is here to assist you with any inquiries or issues you may encounter.</p>
            <br/><p>We are committed to delivering the best service possible and continually improving to meet your needs.</p>
            <br/><p>Once again, thank you for choosing Powerkiosk.ng. We look forward to serving you!</p>
            
            <br/><br/><p>Best regards,</p>
            <br/><p>The Powerkiosk.ng Team</p>
            ---
            
            <br/><br/><p>If you have any questions, feel free to contact our support team at support@powerkiosk.ng or call us at 0707 330 5599.</p>`
        };

          transporter.sendMail(mailOptions, (error, info) => {
            if (error) {
                console.error(error);
                res.status(500).json({ message: 'Email sending failed' });
              } else {
                console.log('Email sent: ' + info.response);
                res.status(200).json({ message: 'Registration successful. Confirmation email sent.' });
              }
          });

    } catch (error) {
        console.log('Email sent: ' + info.response);
        
        res.status(500).send({message: "Internal Server Error. Confirmation email sent."})
    }
})

module.exports = router;