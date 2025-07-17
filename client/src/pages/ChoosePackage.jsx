const packages = [
  { id: 'basic', name: 'Basic Package', price: 10000, desc: '3 foto + 1 stiker' },
  { id: 'premium', name: 'Premium Package', price: 20000, desc: '5 foto + 3 stiker + filter' }
];

export default function ChoosePackage() {
  return (
    <div className="p-6 text-center">
      <h2 className="text-3xl font-bold mb-6">Pilih Paket</h2>
      <div className="flex justify-center gap-10">
        {packages.map(pkg => (
          <div key={pkg.id} className="border p-6 rounded shadow-lg w-64">
            <h3 className="text-xl font-bold mb-2">{pkg.name}</h3>
            <p>{pkg.desc}</p>
            <p className="mt-2 font-semibold text-blue-600">Rp{pkg.price}</p>
            <button
              onClick={() => window.location.href = '/payment'}
              className="mt-4 bg-green-500 px-4 py-2 rounded text-white"
            >Pilih</button>
          </div>
        ))}
      </div>
    </div>
  );
}
