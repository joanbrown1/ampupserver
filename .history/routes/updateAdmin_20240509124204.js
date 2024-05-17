const router = require("express").Router();
const { Admin } = require("../models/Admin");

// PUT endpoint to update admin privilages
router.put("/", async (req, res) => {
    try {
        // Find the admin by email
        const admin = await Admin.findOne({ email: req.body.email });
        if (!admin) return res.status(404).send({ message: "Admin not found" });

        // Update admin data
        if (req.body.privilage) admin.privilage = req.body.privilage;
        await admin.save();

        res.status(200).send({ message: "Admin privilages updated successfully" });
    } catch (error) {
        console.error(error);
        res.status(500).send({ message: "Internal Server Error" });
    }
});

module.exports = router;



