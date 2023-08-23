$(document).ready(function() {
    $("#searchInput").on("input", function() {
        var searchQuery = $(this).val();
        var searchResultsElement = $("#searchResults");
        searchResultsElement.empty();

        if (searchQuery.length >= 2) {
            $.ajax({
                url: "get_users.php",
                type: "GET",
                data: { search: searchQuery },
                success: function(response) {
                    var users = JSON.parse(response);

                    users.forEach(function(user) {
                        var li = $("<li>");
                        li.text(user.nama); 
                        li.attr("data-username", user.username);
                        searchResultsElement.append(li);
                    });
                }
            });
        }
    });
});
