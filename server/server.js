const express = require('express');
const cors = require('cors');
const app = express();

require('dotenv').config();

app.use(cors());
app.use(express.json());
app.use('/uploads', express.static('uploads'));

// routes
app.use('/api/payment', require('./routes/payment'));
app.use('/api/email', require('./routes/email'));

app.listen(5000, () => console.log('Server running on port 5000'));
