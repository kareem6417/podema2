document.addEventListener('DOMContentLoaded', function() {
    var jenisPerangkat = document.getElementById('jenis').value;
    showHideElements(jenisPerangkat);

    document.getElementById('jenis').addEventListener('change', function() {
        var jenisPerangkat = this.value;
        showHideElements(jenisPerangkat);
    });

    function showHideElements(jenisPerangkat) {
        var elementsToShow = [];
        var elementsToHide = [];

        if (jenisPerangkat === 'Laptop') {
            elementsToShow = ['casing_lap', 'layar_lap', 'engsel_lap', 'keyboard_lap', 'touchpad_lap', 'booting_lap', 'multi_lap', 'tampung_lap', 'isi_lap', 'port_lap', 'audio_lap', 'hasil_pemeriksaan', 'screenshot', 'rekomendasi', 'upload_file'];
        } else if (jenisPerangkat === 'PC Desktop') {
            elementsToShow = ['casing_lap', 'layar_lap', 'keyboard_lap', 'booting_lap', 'multi_lap', 'port_lap', 'audio_lap', 'hasil_pemeriksaan', 'screenshot', 'rekomendasi', 'upload_file'];
        } else if (jenisPerangkat === 'Monitor' || jenisPerangkat === 'Printer') {
            elementsToShow = ['casing_lap', 'layar_lap', 'hasil_pemeriksaan', 'screenshot', 'rekomendasi', 'upload_file'];
            if (jenisPerangkat === 'Printer') {
                elementsToShow.push('ink_pad');
            }
        }

        var allElements = ['casing_lap', 'layar_lap', 'engsel_lap', 'keyboard_lap', 'touchpad_lap', 'booting_lap', 'multi_lap', 'tampung_lap', 'isi_lap', 'port_lap', 'software_lap', 'audio_lap', 'hasil_pemeriksaan', 'screenshot', 'rekomendasi', 'upload_file', 'ink_pad'];
        elementsToHide = allElements.filter(element => !elementsToShow.includes(element));

        elementsToHide.forEach(function(elementId) {
            document.getElementById(elementId).style.display = 'none';
        });

        elementsToShow.forEach(function(elementId) {
            document.getElementById(elementId).style.display = 'block';
        });
    }
});
