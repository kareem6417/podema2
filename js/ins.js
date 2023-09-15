    document.getElementById('jenis').addEventListener('change', function() {
        var jenisPerangkat = this.value;
        var casingLabel = document.getElementById('casingLabel');
        var casingSelect = document.getElementById('casing_lap');

        if (jenisPerangkat === 'Laptop' || jenisPerangkat === 'PC Desktop') {
            casingLabel.style.display = 'block';
            casingSelect.style.display = 'block';
        } else {
            casingLabel.style.display = 'none';
            casingSelect.style.display = 'none';
        }
    });