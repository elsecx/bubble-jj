// Remove partial tags
function removePartialTags() {
    $('style[data-partial="1"], script[data-partial="1"]').remove();
}

// Loader helper
function loader(status) {
    $("html").attr("loader", status ? "enable" : "disable");
}

// Link click handler
function setActiveLink(url) {
    $(".spa-link").removeClass("active");
    $(".spa-link").each(function () {
        const href = $(this).attr("href");
        if (url === href) {
            $(this).addClass("active");
        }
    });
}

// Input Telp handler
$("#no_telp").on("input", function () {
    this.value = this.value.replace(/[^0-9]/g, "");
});

// Toggle password handler
function togglePassword(passwordId, toggleBtn) {
    const passwordInput = $("#" + passwordId);
    const type =
        passwordInput.attr("type") === "password" ? "text" : "password";
    passwordInput.attr("type", type);
    $(toggleBtn).find("i").toggleClass("fa-eye fa-eye-slash");
}

// Show toast helper
function showToast(icon, message, timer = 2000) {
    Swal.fire({
        toast: true,
        position: "top-end",
        icon: icon,
        title: message,
        showConfirmButton: false,
        timer: timer,
    });
}

// Confirm password helper
function confirmPassword(onSuccess) {
    const modalEl = document.getElementById("confirmPasswordModal");
    const modal = new bootstrap.Modal(modalEl);
    const form = document.getElementById("form-password-confirm");

    form.reset();
    modal.show();

    $(form)
        .off("submit")
        .on("submit", function (e) {
            e.preventDefault();

            $.ajax({
                url: form.action,
                method: form.method,
                data: $(form).serialize(),
                success: function (res) {
                    if (res.status === "success") {
                        modal.hide();
                        if (typeof onSuccess === "function") {
                            onSuccess(res);
                        }
                    }
                },
                error: function (xhr) {
                    let msg = xhr.responseJSON?.message || "Password salah!";
                    showToast("error", msg);
                },
            });
        });
}

// Load page content
function loadPage(url) {
    loader(true);

    $.get(url, function (response, textStatus, jqXHR) {
        const contentType = jqXHR.getResponseHeader("Content-Type") || "";

        if (!contentType.includes("application/json")) {
            $("#main-content").html(response);
            loader(false);
            return;
        }

        removePartialTags();

        if (response.styles) {
            $("head").append(response.styles);
        }

        $("#main-content").html(response.content || "");

        if (response.scripts) {
            $("body").append(response.scripts);
        }

        if (response.title) {
            document.title = response.title + " - " + window.appName;
            $("#breadcrumb").text(response.title);
        }

        setActiveLink(url);

        loader(false);
    }).fail(function () {
        $("#main-content").html(
            '<h4 class="text-danger">Gagal memuat halaman.</h4>'
        );
        loader(false);
    });
}

// SPA click handler
$(document).on("click", ".spa-link", function (e) {
    const url = $(this).attr("href");
    if (!url || url === "#") return;

    e.preventDefault();
    loadPage(url);
    history.pushState(null, null, url);
});

// Browser back/forward
window.onpopstate = function () {
    loadPage(location.href);
};
