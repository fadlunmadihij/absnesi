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

let awaitingSendToWA = false;
let startDate = "";
let endDate = "";
let IdKelas = "";
let pdfFilePath = "";

// Fungsi utama untuk mendapatkan data dan mengirim PDF
const cek = async () => {
    try {
        const response = await axios.get(
            "http://localhost:8000/api/get-pending-rekap"
        );
        const { data, status } = response.data;

        if (status === "success" && data.length > 0) {
            startDate = data[0].start_date;
            endDate = data[0].end_date;
            IdKelas = data[0].kelas_id;
            console.log(`Mendapatkan data kelas ID: ${IdKelas}`);

            // Ambil nomor WA siswa berdasarkan kelas ID
            const noWaList = await getNoWA(IdKelas);
            if (noWaList.length > 0) {
                const pdfUrl = await generatePDF(IdKelas, startDate, endDate);
                await sendPdfToMultipleWhatsApp(noWaList, pdfUrl);
            }
        }
    } catch (error) {
        console.error("Error mendapatkan data rekap:", error.message);
    }
};

// Fungsi untuk mendapatkan nomor WhatsApp
const getNoWA = async (kelasId) => {
    try {
        const response = await axios.post(
            "http://localhost:8000/api/get-no-wa",
            {
                token: 12345678,
                kelas_id: kelasId,
            }
        );
        const { data, status } = response.data;
        return status === "success" ? data : [];
    } catch (error) {
        console.error("Error mendapatkan nomor WhatsApp:", error.message);
        return [];
    }
};

// Fungsi untuk generate PDF dari API
const generatePDF = async (kelasId, startDate, endDate) => {
    try {
        const response = await axios.post(
            "http://localhost:8000/api/sendPDFtoWA",
            {
                startDate,
                endDate,
                kelasId,
            }
        );
        if (response.status === 200) {
            const pdfUrl = response.data.url.replace(/ /g, "%20");
            return pdfUrl;
        }
    } catch (error) {
        console.error("Error saat generate PDF:", error.message);
        return null;
    }
};

// Fungsi untuk mengirim PDF ke beberapa nomor WhatsApp
const sendPdfToMultipleWhatsApp = async (noWaList, pdfUrl) => {
    try {
        const pdfResponse = await axios.get(pdfUrl, {
            responseType: "arraybuffer",
        });
        pdfFilePath = path.join(__dirname, "rekap.pdf");
        fs.writeFileSync(pdfFilePath, pdfResponse.data);

        const media = MessageMedia.fromFilePath(pdfFilePath);

        await Promise.all(
            noWaList.map(async (noWa) => {
                const formattedNo = formatNomorWA(noWa);
                await client.sendMessage(`${formattedNo}@c.us`, media, {
                    caption: "Berikut rekap kehadiran siswa.",
                });
                console.log(`PDF berhasil dikirim ke nomor: ${formattedNo}`);
            })
        );
    } catch (error) {
        console.error("Error saat mengirim PDF ke WhatsApp:", error.message);
    }
};

// Fungsi untuk format nomor WA
function formatNomorWA(nomor) {
    return nomor.startsWith("08") ? "628" + nomor.slice(1) : nomor;
}

client.on("message", async (message) => {
    const userMessage = message.body;

    if (userMessage === "sendToWali") {
        message.reply("Memproses... Mohon tunggu.");
        await cek();
    } else if (userMessage === ".ping") {
        message.reply("pong");
    } else if (userMessage === ".menu") {
        message.reply("Menu:\n1. .sendRekap - Kirim rekap kehadiran.");
    }
});

client.initialize();
