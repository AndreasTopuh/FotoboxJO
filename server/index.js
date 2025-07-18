require('dotenv').config();
const express = require('express');
const cors = require('cors');
const paymentRoutes = require('./payment');
const path = require('path');

const app = express();
app.use(cors());
app.use(express.json());
app.use('/payment', paymentRoutes);

// Serve static files from React build
app.use(express.static(path.join(__dirname, '../client/dist')));

// All remaining GET requests to serve index.html (support React Router)
app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, '../client/dist/index.html'));
});


app.listen(5000, '0.0.0.0', () => console.log('Server jalan di http://localhost:5000'));
