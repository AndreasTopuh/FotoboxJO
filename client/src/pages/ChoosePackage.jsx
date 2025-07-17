import { useNavigate } from 'react-router-dom';

export default function Package() {
  const navigate = useNavigate();

  const handleSelect = async (packageId) => {
    const res = await fetch('http://localhost:5000/payment/create', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ packageId }),
    });

    const { redirect_url, orderId } = await res.json();

    localStorage.setItem('orderId', orderId);
    window.location.href = redirect_url;
  };

  return (
    <div className="p-10 text-center">
      <h2 className="text-2xl font-bold mb-6">Pilih Paket</h2>
      <div className="grid grid-cols-2 gap-4">
        <button onClick={() => handleSelect('basic')} className="border p-4 rounded">
          Paket Basic - Rp10.000
        </button>
        <button onClick={() => handleSelect('premium')} className="border p-4 rounded">
          Paket Premium - Rp20.000
        </button>
      </div>
    </div>
  );
}
