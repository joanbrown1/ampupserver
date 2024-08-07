const mongoose = require("mongoose");
const jwt = require("jsonwebtoken");
// const passwordComplexity = require("joi-password-complexity");
// const Joi = require("joi");

const userSchema = new mongoose.Schema({
    name: {type:String,  required:true },
    email: {type:String,  required:true },
    phonenumber: {type:String,  required:true },
    meternumber: {type:String,  required:false },
    password: {type:String,  required:true }
});
  

userSchema.methods.generateAuthToken = function () {
    const token = jwt.sign({_id:this._id}, 'AmpUpUser', {
        expiresIn: "1d"
    });
    return token
};

const User = mongoose.model("user", userSchema);

// const validate = (data) => {
//     const schema = Joi.object({
//         email: Joi.string().email().required().label("Email"),
//         phonenumber: Joi.string().required().label("PhoneNumber"),
//         meternumber: Joi.string().required().label("MeterNumber"),
//         password: passwordComplexity().required().label("Password"),
//     });
    
//     return schema.validate(data)
// }

module.exports = {User};
// module.exports = {User, validate};