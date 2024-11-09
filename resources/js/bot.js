import pkg from "whatsapp-web.js";
import qrcode from "qrcode-terminal";
import axios from "axios";
import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

const { Client, LocalAuth, MessageMedia } = pkg;

const client = new Client({
    authStrategy: new LocalAuth(),
});

client.on("qr", (qr) => {
    qrcode.generate(qr, { small: true });
    console.log("Scan QR code untuk login ke WhatsApp Web.");
});

client.on("ready", () => {
    console.log("Bot WhatsApp siap digunakan!");
});

let awaitingDateRange = false;
let awaitingClassSelection = false;
let awaitingShareConfirmation = false;
let startDate = "";
let endDate = "";
let classes = [];
let pdfFilePath = "";

// Function
// Fungsi untuk mengubah awalan '08' menjadi '628'
function formatNomorWA(nomor) {
    if (nomor.startsWith("08")) {
        return "628" + nomor.slice(1);
    }
    return nomor;
}

client.on("message", async (message) => {
    const userId = message.from;
    const userMessage = message.body;

    console.log(`Pesan diterima dari ${userId}: ${userMessage}`);

    if (awaitingDateRange) {
        const dateRange = userMessage.split(" ");
        [startDate, endDate] = dateRange;

        if (
            !/^\d{4}-\d{2}-\d{2}$/.test(startDate) ||
            !/^\d{4}-\d{2}-\d{2}$/.test(endDate)
        ) {
            message.reply(
                "Format tanggal tidak valid. Gunakan format YYYY-MM-DD YYYY-MM-DD. Harap berikan perintah .sendRekap ulang untuk mengulangi permintaan"
            );
            awaitingDateRange = false;
            return;
        }

        try {
            console.log(
                `Mengambil daftar kelas dari API untuk rentang tanggal ${startDate} hingga ${endDate}...`
            );
            const classResponse = await axios.get(
                "http://localhost:8000/api/data-kelas"
            );

            if (classResponse.status !== 200) {
                message.reply("Gagal mengambil data kelas.");
                awaitingDateRange = false;
                return;
            }

            classes = classResponse.data.data;
            let classListMessage = "Pilih kelas terlebih dahulu:\n";
            classes.forEach((kelas, index) => {
                classListMessage += `${index + 1}. ${kelas.nama_kelas}\n`;
            });

            message.reply(classListMessage);
            awaitingClassSelection = true;
            awaitingDateRange = false;
        } catch (error) {
            console.error("Error saat mengambil daftar kelas:", error);
            message.reply("Terjadi kesalahan saat mengambil daftar kelas.");
            awaitingDateRange = false;
        }
    } else if (awaitingClassSelection) {
        const selectedIndex = parseInt(userMessage) - 1;
        if (
            isNaN(selectedIndex) ||
            selectedIndex < 0 ||
            selectedIndex >= classes.length
        ) {
            message.reply("Nomor kelas tidak valid. Silakan coba lagi.");
            return;
        }

        const selectedClassId = classes[selectedIndex].id;
        try {
            console.log(
                `Memproses permintaan rekap untuk kelas ${selectedClassId}...`
            );
            const apiResponse = await axios.post(
                "http://localhost:8000/api/sendPDFtoWA",
                {
                    startDate,
                    endDate,
                    kelasId: selectedClassId,
                }
            );

            // Tangani response error dari API
            if (apiResponse.data.error) {
                console.error(`Error: ${apiResponse.data.error}`);
                message.reply(`Error: ${apiResponse.data.error}`);
                awaitingClassSelection = false;
                return;
            }

            if (apiResponse.status !== 200) {
                message.reply(
                    "Terjadi kesalahan saat mendapatkan rekap kehadiran."
                );
                awaitingClassSelection = false;
                return;
            }
            const encodedUrl = apiResponse.data.url.replace(/ /g, "%20");
            const pdfUrl = encodedUrl;
            // console.log(pdfUrl);
            // console.log(__dirname);
            // return;
            message.reply("Sabar...");

            const pdfResponse = await axios.get(pdfUrl, {
                responseType: "arraybuffer",
            });
            pdfFilePath = path.join(__dirname, apiResponse.data.name + ".pdf");

            fs.writeFileSync(pdfFilePath, pdfResponse.data);
            console.log(`PDF berhasil disimpan di: ${pdfFilePath}`);

            const media = MessageMedia.fromFilePath(pdfFilePath);
            await client.sendMessage(userId, media, {
                caption: `Berikut Rekap Kehadiran siswa/siswi untuk kelas ${classes[selectedIndex].nama_kelas} antara ${startDate} hingga ${endDate}`,
            });

            console.log(`PDF berhasil dikirim ke ${userId}`);
            awaitingClassSelection = false;
            awaitingShareConfirmation = true;
            client.sendMessage(
                userId,
                "Apakah PDF ini akan dibagikan ke ortu/wali siswa? Balas 'iya' untuk mengirim."
            );
        } catch (error) {
            console.error("Error saat mengirim PDF:", error);
            let errorMsg = error.response;

            if (errorMsg == undefined) {
                message.reply(
                    "Terjadi kesalahan saat mengirim rekap kehadiran."
                );
            } else {
                message.reply(`${error.response.data.error}`);
            }
            awaitingClassSelection = false;
        }
    } else if (awaitingShareConfirmation) {
        if (userMessage.toLowerCase() === "iya") {
            try {
                // Fetch nomor WA dari API data-nomer
                const response = await axios.post(
                    "http://localhost:8000/api/data-nomer",
                    {
                        token: "12345678",
                    }
                );

                if (response.status === 200) {
                    const nomorWaList = response.data.data;

                    for (const nomor of nomorWaList) {
                        const formattedNomor = formatNomorWA(nomor); // Format nomor sebelum mengirim pesan
                        const media = MessageMedia.fromFilePath(pdfFilePath);
                        await client.sendMessage(
                            `${formattedNomor}@c.us`,
                            media,
                            {
                                caption: "Berikut rekap kehadiran siswa.",
                            }
                        );
                        console.log(
                            `PDF berhasil dikirim ke nomor: ${formattedNomor}`
                        );
                    }

                    message.reply(
                        "PDF berhasil dibagikan ke orang tua/wali siswa."
                    );
                } else {
                    message.reply("Gagal mengambil data nomor WA.");
                }
            } catch (error) {
                console.error("Error saat mengambil nomor WA:", error);
                message.reply(
                    "Terjadi kesalahan saat mengambil data nomor WA."
                );
            }
            awaitingShareConfirmation = false;
        } else {
            message.reply("Permintaan dibatalkan. PDF tidak dibagikan.");
            awaitingShareConfirmation = false;
        }
    } else if (userMessage === ".ping") {
        message.reply("pong");
        console.log(`Bot membalas ke ${userId}: pong`);
    } else if (userMessage === ".menu") {
        message.reply("Menu:\n1. .sendRekap - Kirim rekap kehadiran.");
        console.log(`Bot menampilkan menu ke ${userId}`);
    } else if (userMessage === ".sendRekap") {
        awaitingDateRange = true;
        message.reply(
            "Silakan masukkan rentang waktu dengan format YYYY-MM-DD YYYY-MM-DD. Contoh: 2024-07-01 2024-07-31"
        );
        console.log(`Bot meminta rentang waktu dari ${userId}`);
    }
});

// bot.js
if (typeof window !== "undefined") {
    window.kirimRekapKeWA = function (rekapData) {
        console.log("Mengirim rekap via WA:", rekapData);
        // Logika tambahan jika diperlukan
    };
}

client.initialize();
