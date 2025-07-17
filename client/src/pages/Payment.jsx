// Payment.jsx
import { useEffect } from 'react';

export default function Payment() {
  useEffect(() => {
    const packageId = localStorage.getItem('selectedPackage');

    fetch('http://localhost:5000/payment/token', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ packageId }),
    })
      .then(res => res.json())
      .then(({ token }) => {
        window.snap.pay(token, {
          onSuccess: () => (window.location.href = '/camera'),
          onPending: () => alert('Menunggu pembayaran...'),
          onError: () => alert('Gagal'),
        });
      });
  }, []);

  return (
    <div className="h-screen flex items-center justify-center">
      <h1 className="text-xl text-center">Mempersiapkan pembayaran...</h1>
    </div>
  );
}
