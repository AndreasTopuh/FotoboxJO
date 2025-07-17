// server.js
import express from 'express';
import cors from 'cors';
import dotenv from 'dotenv';
import paymentRoutes from './routes/payment.js';

dotenv.config();
const app = express();

app.use(cors());
app.use(express.json());
app.use('/payment', paymentRoutes);

app.listen(5000, () => console.log('Server jalan di port 5000'));
