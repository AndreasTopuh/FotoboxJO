import { useNavigate } from 'react-router-dom';
import { useState } from 'react';

export default function SelectPayment() {
  const navigate = useNavigate();
  const [selectedMethod, setSelectedMethod] = useState(null);

  const handleSelectMethod = (method) => {
    setSelectedMethod(method);
    localStorage.setItem('payment_method', method);
    navigate('/payment');
  };

  return (
    <div className="h-screen w-screen flex items-center justify-center">
      <div className="backdrop-blur-md bg-white/10 border border-white/20 text-white rounded-xl p-10 max-w-md w-[90vw] shadow-2xl space-y-6 text-center">
        <h1 className="text-3xl font-bold">Pilih Metode Pembayaran</h1>
        <p className="text-lg">Harga layanan Fotobox: <strong className="text-pink-400">Rp15.000</strong></p>
        <p className="text-sm text-white/70">Silakan pilih metode pembayaran di bawah ini</p>

        <div className="flex flex-col gap-4">
          <button
            onClick={() => handleSelectMethod('qris')}
            className="bg-pink-500 hover:bg-pink-600 transition px-6 py-3 rounded-full font-semibold shadow-md"
          >
            Bayar via QRIS
          </button>

          <button
            onClick={() => handleSelectMethod('bank_transfer')}
            className="bg-blue-500 hover:bg-blue-600 transition px-6 py-3 rounded-full font-semibold shadow-md"
          >
            Bayar via Virtual Account BCA
          </button>
        </div>
      </div>
    </div>
  );
}
