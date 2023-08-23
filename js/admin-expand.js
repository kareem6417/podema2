
    function toggleInspeksi(count) {
        var content = document.getElementById("inspeksi-" + count);
        if (content.style.display === "none") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }

    function toggleAssessment(count) {
        var content = document.getElementById("assessment-" + count);
        if (content.style.display === "none") {
            content.style.display = "block";
        } else {
            content.style.display = "none";
        }
    }

    function toggleAssessmentPC(assessmentNumber) {
        var assessmentContent = document.getElementById('assessment-pc-' + assessmentNumber);
        if (assessmentContent.style.display === 'none') {
            assessmentContent.style.display = 'block';
        } else {
            assessmentContent.style.display = 'none';
        }
    }
