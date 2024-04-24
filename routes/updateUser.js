const router = require("express").Router();
const { User } = require("../models/user");
const bcrypt = require("bcrypt");

// PUT endpoint to update user data, including password
router.put("/", async (req, res) => {
    try {
        // Find the user by email
        const user = await User.findOne({ email: req.body.email });
        if (!user) return res.status(404).send({ message: "User not found" });

        // Update user data
        if (req.body.phonenumber) user.phonenumber = req.body.phonenumber;
        if (req.body.meternumber) user.meternumber = req.body.meternumber;
        if (req.body.password) {
            const salt = await bcrypt.genSalt(10);
            const hashPassword = await bcrypt.hash(req.body.password, salt);
            user.password = hashPassword;
        }
        await user.save();

        res.status(200).send({ message: "User data updated successfully" });
    } catch (error) {
        console.error(error);
        res.status(500).send({ message: "Internal Server Error" });
    }
});

module.exports = router;
