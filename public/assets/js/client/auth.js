$(document).ready(function () {
    $(".toggle-password").on("click", function () {
        let $input = $(this).closest(".input-group").find("input");
        let $icon_show = $(this).find(".icon-show");
        let $icon_hide = $(this).find(".icon-hide");

        if ($input.attr("type") === "password") {
            $input.attr("type", "text");
            $icon_show.hide();
            $icon_hide.show();
        } else {
            $input.attr("type", "password");
            $icon_show.show();
            $icon_hide.hide();
        }
    });
    $("#password").on("input", function () {
        let val = $(this).val();
        let strengthBar = $("#passwordStrength");
        let strengthText = $("#passwordStrengthText");
        let strength = 0;

        if (val.length >= 8) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        switch (strength) {
            case 0:
                strengthBar.css("width", "0%");
                strengthText
                    .text("Nhập mật khẩu để đánh giá độ mạnh")
                    .removeClass()
                    .addClass("text-muted");
                break;
            case 1:
                strengthBar
                    .css("width", "25%")
                    .removeClass()
                    .addClass("progress-bar bg-danger");
                strengthText
                    .text("Yếu – dễ bị đoán")
                    .removeClass()
                    .addClass("text-danger fw-semibold");
                break;
            case 2:
                strengthBar
                    .css("width", "50%")
                    .removeClass()
                    .addClass("progress-bar bg-warning");
                strengthText
                    .text("Trung bình – nên thêm số và ký tự đặc biệt")
                    .removeClass()
                    .addClass("text-warning fw-semibold");
                break;
            case 3:
                strengthBar
                    .css("width", "75%")
                    .removeClass()
                    .addClass("progress-bar bg-info");
                strengthText
                    .text("Khá tốt – gần đạt an toàn")
                    .removeClass()
                    .addClass("text-info fw-semibold");
                break;
            case 4:
                strengthBar
                    .css("width", "100%")
                    .removeClass()
                    .addClass("progress-bar bg-success");
                strengthText
                    .text("Rất mạnh – mật khẩu an toàn")
                    .removeClass()
                    .addClass("text-success fw-semibold");
                break;
        }
    });
});
