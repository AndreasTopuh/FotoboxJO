// Package.jsx
import { useNavigate } from 'react-router-dom';

export default function Package() {
  const navigate = useNavigate();

  const handleSelectPackage = async (priceId) => {
    // Simpan ID paket ke localStorage (sementara)
    localStorage.setItem('selectedPackage', priceId);
    navigate('/payment');
  };

  return (
    <div className="p-10 text-center">
      <h2 className="text-2xl font-bold mb-6">Pilih Paket</h2>
      <div className="grid grid-cols-2 gap-6">
        <button
          onClick={() => handleSelectPackage('basic')}
          className="border p-6 rounded-lg hover:bg-gray-100"
        >
          Paket Basic - Rp10.000
        </button>
        <button
          onClick={() => handleSelectPackage('premium')}
          className="border p-6 rounded-lg hover:bg-gray-100"
        >
          Paket Premium - Rp20.000
        </button>
      </div>
    </div>
  );
}
