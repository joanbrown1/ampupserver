const router = require("express").Router();
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

router.post("/", async (req, res) => {
  const email = req.body.email;
  const subject = req.body.subject;
  const message = req.body.message;

  const mailOptions = {
    from: email,
    to: 'support@powerkiosk.ng', 
    subject: subject,
    text: message
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
});

module.exports = router;
