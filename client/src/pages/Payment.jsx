import { useEffect } from 'react';

export default function Payment() {
  useEffect(() => {
    // Simulasikan Midtrans Snap muncul
    setTimeout(() => {
      alert('Pembayaran berhasil (dummy)');
      window.location.href = '/frame';
    }, 2000);
  }, []);

  return (
    <div className="h-screen flex items-center justify-center">
      <h1 className="text-xl">Mempersiapkan QRIS...</h1>
    </div>
  );
}
