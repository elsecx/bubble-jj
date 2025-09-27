function initUploadHandler(options) {
    const {
        formSelector = "#form-upload",
        buttonText = "Upload Sekarang",
        rules = {}, // { max_size, min_duration, max_duration }
        previewSelector = null, // container preview
        previewType = "auto", // auto | image | video
        multiple = false, // true kalau photo
        passwordCheckUrl,
    } = options;

    const $form = $(formSelector);
    const $btnSubmit = $form.find("button[type=submit]");
    const $fileInput = $form.find("input[name='file'], input[name='files[]']");
    const $previewContainer = previewSelector ? $(previewSelector) : null;

    let filesArray = [];

    function toggleButton(state, text = buttonText) {
        $btnSubmit.prop("disabled", !state).text(state ? text : "Loading...");
    }

    /** Render file preview (single/multiple) */
    function renderPreview(
        files,
        container,
        type = "auto",
        multiple = false,
        inputEl = null
    ) {
        container.html("");
        if (!files) return;

        const fileList = multiple ? files : [files];

        fileList.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function (e) {
                const wrapper = $("<div>")
                    .addClass("position-relative m-2")
                    .css({
                        width: multiple ? "120px" : "auto",
                        height: multiple ? "120px" : "auto",
                    });

                let previewElement;
                let fileType = type;

                if (fileType === "auto") {
                    if (file.type.startsWith("image/")) fileType = "image";
                    else if (file.type.startsWith("video/")) fileType = "video";
                }

                if (fileType === "image") {
                    previewElement = $("<img>")
                        .attr("src", e.target.result)
                        .addClass("border")
                        .css({
                            width: "100%",
                            height: "100%",
                            objectFit: "cover",
                            borderRadius: "8px",
                        });
                } else if (fileType === "video") {
                    previewElement = $("<video>")
                        .attr("src", e.target.result)
                        .attr("controls", true)
                        .css({
                            width: "100%",
                            height: "100%",
                            borderRadius: "8px",
                            objectFit: "cover",
                        });
                } else {
                    previewElement = $(
                        '<span class="text-danger">Format tidak didukung</span>'
                    );
                }

                const removeBtn = $("<button>")
                    .addClass(
                        "btn btn-sm btn-danger position-absolute top-0 end-0 m-1 p-1"
                    )
                    .html('<i class="mdi mdi-close"></i>')
                    .on("click", function () {
                        if (multiple && Array.isArray(files)) {
                            files.splice(i, 1);
                            if (inputEl) {
                                const dt = new DataTransfer();
                                files.forEach((f) => dt.items.add(f));
                                inputEl.files = dt.files;
                            }
                            renderPreview(
                                files,
                                container,
                                type,
                                multiple,
                                inputEl
                            );
                        } else {
                            container.html("");
                            if (inputEl) inputEl.value = "";
                        }
                    });

                wrapper.append(previewElement).append(removeBtn);
                container.append(wrapper);
            };
            reader.readAsDataURL(file);
        });
    }

    function validateFile(file, callback) {
        if (!file) {
            showToast("error", "Pilih file terlebih dahulu!");
            toggleButton(true);
            return;
        }

        if (
            file.type.startsWith("video/") &&
            (rules.max_duration || rules.min_duration)
        ) {
            let video = document.createElement("video");
            video.preload = "metadata";
            video.src = URL.createObjectURL(file);

            video.onloadedmetadata = function () {
                window.URL.revokeObjectURL(video.src);

                if (rules.max_size && file.size > rules.max_size) {
                    showToast(
                        "error",
                        `Ukuran video melebihi ${Math.round(
                            rules.max_size / 1024 / 1024
                        )}MB!`
                    );
                    toggleButton(true);
                    return;
                }

                if (rules.min_duration && video.duration < rules.min_duration) {
                    showToast(
                        "error",
                        `Durasi video minimal ${rules.min_duration} detik!`
                    );
                    toggleButton(true);
                    return;
                }

                if (rules.max_duration && video.duration > rules.max_duration) {
                    showToast(
                        "error",
                        `Durasi video maksimal ${rules.max_duration} detik!`
                    );
                    toggleButton(true);
                    return;
                }

                callback();
            };
        } else {
            if (rules.max_size && file.size > rules.max_size) {
                showToast(
                    "error",
                    `Ukuran file melebihi ${Math.round(
                        rules.max_size / 1024 / 1024
                    )}MB!`
                );
                toggleButton(true);
                return;
            }
            callback();
        }
    }

    function submitAjax() {
        let formData = new FormData($form[0]);
        $.ajax({
            url: $form.attr("action"),
            type: $form.attr("method"),
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === "success") {
                    $form[0].reset();
                    if ($previewContainer) $previewContainer.html("");
                    showToast("success", res.message);
                } else {
                    showToast("error", res.message);
                }
                toggleButton(true);
            },
            error: function (xhr) {
                if (xhr.status === 422 && xhr.responseJSON.errors) {
                    const errors = Object.values(xhr.responseJSON.errors)
                        .flat()
                        .join("<br>");
                    showToast(
                        "error",
                        errors || "Data yang dimasukkan tidak valid"
                    );
                } else {
                    showToast(
                        "error",
                        xhr.responseJSON?.message ||
                            "Terjadi kesalahan, coba lagi."
                    );
                }
                toggleButton(true);
            },
        });
    }

    // Event submit
    $form.on("submit", function (e) {
        e.preventDefault();
        toggleButton(false);

        let processUpload = () => {
            if (multiple) {
                if (filesArray.length === 0) {
                    showToast("error", "Pilih minimal 1 file!");
                    toggleButton(true);
                    return;
                }
                let checkNext = (i) => {
                    if (i >= filesArray.length) return submitAjax();
                    validateFile(filesArray[i], () => checkNext(i + 1));
                };
                checkNext(0);
            } else {
                let fileInput = $fileInput[0]?.files[0];
                validateFile(fileInput, submitAjax);
            }
        };

        if (passwordCheckUrl) {
            $.get(passwordCheckUrl, function (res) {
                if (res.confirmed) processUpload();
                else confirmPassword(processUpload);
            }).fail(function () {
                showToast("error", "Gagal memeriksa password.");
                toggleButton(true);
            });
        } else {
            processUpload();
        }
    });

    // Event preview
    if ($previewContainer && $fileInput.length) {
        $fileInput.on("change", function () {
            if (multiple) {
                filesArray = Array.from(this.files);
                renderPreview(
                    filesArray,
                    $previewContainer,
                    previewType,
                    true,
                    this
                );
            } else {
                let file = this.files[0];
                renderPreview(
                    file,
                    $previewContainer,
                    previewType,
                    false,
                    this
                );
            }
        });
    }
}
