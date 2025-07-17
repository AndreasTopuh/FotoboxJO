import { useEffect } from 'react';
import { useNavigate } from 'react-router-dom';

export default function Verify() {
  const navigate = useNavigate();

  useEffect(() => {
    const orderId = localStorage.getItem('orderId');

    const interval = setInterval(async () => {
      const res = await fetch(`http://localhost:5000/payment/status/${orderId}`);
      const { status } = await res.json();

      if (status === 'settlement') {
        clearInterval(interval);
        navigate('/camera');
      } else if (status === 'expire') {
        clearInterval(interval);
        alert('Waktu pembayaran habis, silakan ulangi.');
        navigate('/package');
      }
    }, 3000);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="flex flex-col items-center justify-center h-screen">
      <h1 className="text-xl">Menunggu konfirmasi pembayaran...</h1>
    </div>
  );
}
