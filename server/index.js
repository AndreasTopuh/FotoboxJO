require('dotenv').config();
const express = require('express');
const cors = require('cors');
const paymentRoutes = require('./payment');
const path = require('path');

const app = express();
app.use(cors({
  origin: ['http://localhost:5173', 'https://gofotobox.online'],
  methods: ['GET', 'POST'],
  credentials: true,
}));
app.use(express.json());

//halo

app.use('/payment', paymentRoutes);

app.use(express.static(path.join(__dirname, '../client/dist')));

app.get('*', (req, res) => {
  res.sendFile(path.join(__dirname, '../client/dist/index.html'));
});


app.listen(5000, '0.0.0.0', () => console.log('Server jalan di http://localhost:5000'));