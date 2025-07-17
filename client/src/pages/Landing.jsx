export default function Landing() {
  return (
    <div className="h-screen flex flex-col justify-center items-center bg-gray-900 text-white">
      <h1 className="text-4xl font-bold mb-4">Welcome to PhotoBooth Online!</h1>
      <button onClick={() => window.location.href = '/paket'} className="bg-blue-500 px-6 py-2 rounded hover:bg-blue-700">
        Mulai
      </button>
    </div>
  );
}
