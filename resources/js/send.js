// resources/js/send.js

import { kirimRekapKeWA } from "./bot.js"; // Mengimpor fungsi dari bot.js

// Misalnya Anda ingin mengirim rekap saat pengguna menekan tombol
document.getElementById("kirimRekapButton").addEventListener("click", () => {
    const rekapData = {
        tanggal: "2024-11-01",
        kelas: "10A",
        status: "Hadir",
    };

    kirimRekapKeWA(rekapData); // Memanggil fungsi untuk mengirim rekap
});
