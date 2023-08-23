$(document).ready(function() {
            $(".popup-trigger").click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var company = $(this).data('company');
                var divisi = $(this).data('divisi');
                var merk = $(this).data('merk');
                var serialnumber = $(this).data('serialnumber');
                var typepc = $(this).data('pctype_name');
                var typepcScore = $(this).data('pctype_score');
                var os = $(this).data('os_name');
                var osScore = $(this).data('os_score');
                var processor = $(this).data('processor_name');
                var processorScore = $(this).data('processor_score');
                var vga = $(this).data('vga_name');
                var vgaScore = $(this).data('vga_score');
                var age = $(this).data('age_name');
                var ageScore = $(this).data('age_score');
                var issue = $(this).data('issue_name');
                var issueScore = $(this).data('issue_score');
                var ram = $(this).data('ram_name');
                var ramScore = $(this).data('ram_score');
                var storage = $(this).data('storage_name');
                var storageScore = $(this).data('storage_score');
                var typemonitor = $(this).data('monitor_name');
                var typemonitorScore = $(this).data('monitor_score');
                var sizemonitor = $(this).data('size_name');
                var sizemonitorScore = $(this).data('size_score');
                var score = $(this).data('score');

                $("#popup-id").text(id);
                $("#popup-name").text(name);
                $("#popup-company").text(company);
                $("#popup-divisi").text(divisi);
                $("#popup-merk").text(merk);
                $("#popup-serialnumber").text(serialnumber);
                $("#popup-pctype_name").text(typepc + " \t (" + typepcScore + ")");
                $("#popup-os_name").text(os + " \t (" + osScore + ")");
                $("#popup-processor_name").text(processor + " \t (" + processorScore + ")");
                $("#popup-vga_name").text(vga + " \t (" + vgaScore + ")");
                $("#popup-age_name").text(age + " \t (" + ageScore +")");
                $("#popup-issue_name").text(issue + " \t (" + issueScore + ")");
                $("#popup-ram_name").text(ram + " \t (" + ramScore + ")");
                $("#popup-storage_name").text(storage + " \t (" + storageScore + ")");
                $("#popup-monitor_name").text(typemonitor + " \t (" + typemonitorScore + ")");
                $("#popup-size_name").text(sizemonitor + " \t (" + sizemonitorScore + ")");
                $("#popup-score").text(score);
                $(".popup-overlay, .popup-content").fadeIn();
            });

            $(".popup-overlay").click(function() {
                $(".popup-overlay, .popup-content").fadeOut();
            });

            $(".scroll-to-top").click(function() {
                $("html, body").animate({ scrollTop: 0 }, "slow");
            });
        });