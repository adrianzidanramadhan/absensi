<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Scan QR</title>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="p-5">

<h1 class="text-xl font-bold mb-4">Scan QR Code</h1>

<div id="reader" style="width:300px;"></div>
<div id="message" class="mt-4 font-semibold"></div>

<script>
    const html5QrCode = new Html5Qrcode("reader");

    function onScanSuccess(decodedText, decodedResult) {
        html5QrCode.stop();

        axios.post("{{ route('scan.submit') }}", {
            siswa_id: decodedText,
            _token: "{{ csrf_token() }}"
        })
        .then(res => {
            document.getElementById("message").innerText = res.data.message;
            setTimeout(startScanner, 2000);
        })
        .catch(err => {
            document.getElementById("message").innerText = err.response.data.message || "Error";
            setTimeout(startScanner, 2000);
        });
    }

    function startScanner() {
        html5QrCode.start(
            { facingMode: "environment" },
            { fps: 10, qrbox: 250 },
            onScanSuccess
        );
    }

    startScanner();
</script>

</body>
</html>
