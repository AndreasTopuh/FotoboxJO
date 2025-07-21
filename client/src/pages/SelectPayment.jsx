import { useEffect, useState } from 'react';
import { useNavigate } from 'react-router-dom';

export default function SelectPayment() {
  const navigate = useNavigate();
  const [timer, setTimer] = useState(300); // 5 menit timer
  const [selectedMethod, setSelectedMethod] = useState(null);

  // Mulai timer session
  useEffect(() => {
    const interval = setInterval(() => {
      setTimer((prev) => {
        if (prev <= 1) {
          clearInterval(interval);
          localStorage.removeItem('payment_method');
          localStorage.removeItem('session_timer');
          navigate('/'); // Kembali ke Landing jika waktu habis
        }
        return prev - 1;
      });
    }, 1000);
    localStorage.setItem('session_timer', timer);
    return () => clearInterval(interval);
  }, [timer, navigate]);

  // Simpan pilihan metode pembayaran dan navigasi ke PaymentScreen
  const handleSelectMethod = (method) => {
    setSelectedMethod(method);
    localStorage.setItem('payment_method', method);
    navigate('/payment');
  };

  return (
    <div className="h-screen flex flex-col items-center justify-center bg-gray-50">
      <div className="text-center max-w-md mx-auto px-4">
        <h1 className="text-3xl font-bold mb-4 text-gray-800">Pilih Metode Pembayaran</h1>
        <p className="mb-2 text-lg text-gray-600">Jumlah: <strong>Rp15.000</strong></p>
        <p className="mb-4 text-sm text-red-600">
          Batas waktu pembayaran: {Math.floor(timer / 60)}:{String(timer % 60).padStart(2, '0')}
        </p>
        <div className="flex flex-col gap-4">
          <button
            onClick={() => handleSelectMethod('qris')}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded transition w-full"
          >
            Bayar via QRIS
          </button>
          <button
            onClick={() => handleSelectMethod('bank_transfer')}
            className="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded transition w-full"
          >
            Bayar via Virtual Account Bank
          </button>
        </div>
      </div>
    </div>
  );
}