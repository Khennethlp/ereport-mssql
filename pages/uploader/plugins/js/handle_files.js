document.addEventListener("DOMContentLoaded", function(){
    // handle file uploading for updating
    var u_fileDropArea = $("#u_fileDropArea");
    var file_update = $("#file_update");

    u_fileDropArea.on("dragover", function(e) {
        e.preventDefault();
        e.stopPropagation();
        u_fileDropArea.addClass("dragover");
    });

    u_fileDropArea.on("dragleave", function(e) {
        e.preventDefault();
        e.stopPropagation();
        u_fileDropArea.removeClass("dragover");
    });

    u_fileDropArea.on("drop", function(e) {
        e.preventDefault();
        e.stopPropagation();
        u_fileDropArea.removeClass("dragover");
        var files = e.originalEvent.dataTransfer.files;
        handleFiles(files);
    });

    file_update.on("change", function() {
        var files = file_update[0].files;
        handleFiles(files);
    });

    function handleFiles(files) {
        // Allow only one file to be selected at a time
        var selectedFile = files[0];
        console.log("Selected file:", selectedFile); // Debug log
        updateFileInput(selectedFile);
        updateLabel(selectedFile);
    }

    function updateFileInput(selectedFile) {
        var dataTransfer = new DataTransfer();
        dataTransfer.items.add(selectedFile);
        file_update[0].files = dataTransfer.files;
    }

    function updateLabel(selectedFile) {
        console.log("Updating label for file:", selectedFile); // Debug log
        var fileName = selectedFile ? selectedFile.name : "Click or Drop file here";
        u_fileDropArea.find("p").text(fileName);
    }

})
