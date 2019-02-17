const express = require('express');
const router = express.Router();

const authController = require('../controllers/auth');

// user registration
router.post('/register', authController.registerUser);
router.get('/login', authController.loginUser);

module.exports = router;
