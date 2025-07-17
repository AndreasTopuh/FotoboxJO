require('dotenv').config();
const express = require('express');
const cors = require('cors');
const paymentRoutes = require('./payment');

const app = express();
app.use(cors());
app.use(express.json());
app.use('/payment', paymentRoutes);

app.listen(5000, () => console.log('Server jalan di http://localhost:5000'));
