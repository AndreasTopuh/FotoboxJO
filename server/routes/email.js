const express = require('express');
const router = express.Router();
const { sendEmail } = require('../utils/mailer');

router.post('/send', sendEmail);

module.exports = router;
