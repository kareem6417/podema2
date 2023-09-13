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
        var rekomDiv = document.getElementById('rekomDiv');

        if(selectedJenis === 'Laptop') {
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
            rekomDiv.style.display = 'block';
        } else if(selectedJenis === 'PC Desktop') {
            infoDiv.style.display = 'block';
            bootingDiv.style.display = 'block';
            multiDiv.style.display = 'block';
            tampungDiv.style.display = 'block';
            isiDiv.style.display = 'block';
            portDiv.style.display = 'block';
            audioDiv.style.display = 'block';
            softwareDiv.style.display = 'block';
            rekomDiv.style.display = 'block';
        } else {
            casingDiv.style.display = 'none';
            layarDiv.style.display = 'none';
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
        }
    });