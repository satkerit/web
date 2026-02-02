// Alpine.js Component Functions
// These must be defined before Alpine.js initializes

// File Upload Component
window.fileUpload = function (name, currentFile, maxSize) {
    return {
        hasFile: false,
        hasError: false,
        isImage: false,
        fileName: "",
        fileSize: "",
        previewUrl: "",
        errorMessage: "",
        currentFileUrl: currentFile,
        currentFileName: currentFile ? currentFile.split("/").pop() : "",
        shouldDelete: "0",

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (!file) {
                this.resetFile();
                return;
            }

            // Validate file size
            if (file.size > maxSize * 1024) {
                this.errorMessage = `File terlalu besar. Maksimal ${maxSize}KB`;
                this.hasError = true;
                this.resetFile();
                return;
            }

            this.hasFile = true;
            this.hasError = false;
            this.errorMessage = "";
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);
            this.shouldDelete = "0";

            // Check if image for preview
            if (file.type.startsWith("image/")) {
                this.isImage = true;
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                };
                reader.readAsDataURL(file);
            } else {
                this.isImage = false;
            }
        },

        removeFile() {
            this.resetFile();
            document.getElementById(name).value = "";
        },

        removeCurrentFile() {
            this.shouldDelete = "1";
            this.currentFileUrl = "";
            this.currentFileName = "";
        },

        resetFile() {
            this.hasFile = false;
            this.hasError = false;
            this.isImage = false;
            this.fileName = "";
            this.fileSize = "";
            this.previewUrl = "";
            this.errorMessage = "";
        },

        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (
                parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
            );
        },
    };
};

// Company Info Form Component
window.companyInfoForm = function () {
    return {
        init() {
            console.log("Company Info Form initialized");
        },
    };
};
