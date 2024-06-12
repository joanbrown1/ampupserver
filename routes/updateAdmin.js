const router = require("express").Router();
const { Admin } = require("../models/Admin");
const bcrypt = require("bcrypt");

// PUT endpoint to update admin privilages
router.put("/", async (req, res) => {
    try {
        // Find the admin by email
        const admin = await Admin.findOne({ email: req.body.email });
        if (!admin) return res.status(404).send({ message: "Admin not found" });

        // Update admin data
        if (req.body.privilage) admin.privilage = req.body.privilage;
        if (req.body.password) {
            const salt = await bcrypt.genSalt(10);
            const hashPassword = await bcrypt.hash(req.body.password, salt);
            admin.password = hashPassword;
        }
        await admin.save();

        res.status(200).send({ message: "Admin updated successfully" });
    } catch (error) {
        console.error(error);
        res.status(500).send({ message: "Internal Server Error" });
    }
});

module.exports = router;



