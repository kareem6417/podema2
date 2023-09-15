    document.getElementById('jenis').addEventListener('change', function() {
        var selectedJenis = this.value;
        var infoDiv = document.getElementById('infoDiv');
        var casingDiv = document.getElementById('casingDiv');
        var layarDiv = document.getElementById('layarDiv');
        var engselDiv = document.getElementById('engselDiv');
        var keyboardDiv = document.getElementById('keyboardDiv');
        var touchpadDiv = document.getElementById('touchpadDiv');
        var bootingDiv = document.getElementById('bootingDiv');
        var multiDiv = document.getElementById('multiDiv');
        var tampungDiv = document.getElementById('tampungDiv');
        var isiDiv = document.getElementById('isiDiv');
        var portDiv = document.getElementById('portDiv');
        var audioDiv = document.getElementById('audioDiv');
        var softwareDiv = document.getElementById('softwareDiv');
        var inkpadDiv = document.getElementById('inkpadDiv');
        var rekomDiv = document.getElementById('rekomDiv');

        // Sembunyikan semua elemen terlebih dahulu
        hideAllDivs();

        // Tampilkan elemen berdasarkan jenis perangkat yang dipilih
        if (selectedJenis === 'Laptop') {
            infoDiv.style.display = 'block';
            casingDiv.style.display = 'block';
            layarDiv.style.display = 'block';
            engselDiv.style.display = 'block';
            keyboardDiv.style.display = 'block';
            touchpadDiv.style.display = 'block';
            bootingDiv.style.display = 'block';
            multiDiv.style.display = 'block';
            tampungDiv.style.display = 'block';
            isiDiv.style.display = 'block';
            portDiv.style.display = 'block';
            audioDiv.style.display = 'block';
            softwareDiv.style.display = 'block';
            inkpadDiv.style.display = 'none';
            rekomDiv.style.display = 'block';
        } else if (selectedJenis === 'PC Desktop') {
            infoDiv.style.display = 'block';
            casingDiv.style.display = 'block'; // Tampilkan casing untuk PC Desktop
            layarDiv.style.display = 'block'; // Tampilkan layar untuk PC Desktop
            engselDiv.style.display = 'none';
            keyboardDiv.style.display = 'block'; // Tampilkan keyboard untuk PC Desktop
            touchpadDiv.style.display = 'none';
            bootingDiv.style.display = 'block';
            multiDiv.style.display = 'block';
            tampungDiv.style.display = 'none';
            isiDiv.style.display = 'none';
            portDiv.style.display = 'block'; // Tampilkan port untuk PC Desktop
            audioDiv.style.display = 'none';
            softwareDiv.style.display = 'block'; // Tampilkan software untuk PC Desktop
            inkpadDiv.style.display = 'none';
            rekomDiv.style.display = 'block';
        } else if (selectedJenis === 'Monitor') {
            infoDiv.style.display = 'block';
            casingDiv.style.display = 'block'; // Tampilkan casing untuk Monitor
            layarDiv.style.display = 'block'; // Tampilkan layar untuk Monitor
            engselDiv.style.display = 'none';
            keyboardDiv.style.display = 'none';
            touchpadDiv.style.display = 'none';
            bootingDiv.style.display = 'none';
            multiDiv.style.display = 'none';
            tampungDiv.style.display = 'none';
            isiDiv.style.display = 'none';
            portDiv.style.display = 'none';
            audioDiv.style.display = 'none';
            softwareDiv.style.display = 'none';
            inkpadDiv.style.display = 'none';
            rekomDiv.style.display = 'block';
        } else if (selectedJenis === 'Printer') {
            infoDiv.style.display = 'block';
            casingDiv.style.display = 'block'; // Tampilkan casing untuk Printer
            layarDiv.style.display = 'block'; // Tampilkan layar untuk Printer
            engselDiv.style.display = 'none';
            keyboardDiv.style.display = 'none';
            touchpadDiv.style.display = 'none';
            bootingDiv.style.display = 'none';
            multiDiv.style.display = 'none';
            tampungDiv.style.display = 'none';
            isiDiv.style.display = 'none';
            portDiv.style.display = 'none';
            audioDiv.style.display = 'none';
            softwareDiv.style.display = 'none';
            inkpadDiv.style.display = 'block'; // Tampilkan inkpad untuk Printer
            rekomDiv.style.display = 'block';
        }
    });

    function hideAllDivs() {
        var divs = document.querySelectorAll('.content > div');
        for (var i = 0; i < divs.length; i++) {
            divs[i].style.display = 'none';
        }
    }

    // Panggil fungsi untuk menyembunyikan semua elemen saat halaman pertama kali dimuat
    hideAllDivs();