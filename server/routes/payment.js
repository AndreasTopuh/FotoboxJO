const express = require('express');
const router = express.Router();
const { createTransaction } = require('../utils/midtrans');
router.post('/create', createTransaction);
module.exports = router;