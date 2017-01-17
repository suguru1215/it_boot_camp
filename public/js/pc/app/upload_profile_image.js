"use strict";

// 画像のプレビュー表示
$(function() {
    // プロフィール編集画面
    if ($("input[id^=upload_profile_image]").length > 0) {
        $("input[id^=upload_profile_image]").previewProfileImage();
    }
});

// 画像のアップロード
$.fn.previewProfileImage = function () {
    $(this).on("change", function () {
        var self = $(this);
        var target_form = self.parent("form");
        var fd = new FormData(target_form[0]);
        var token = $("#csrf_token").val();

        // upload
        $.ajax({
            url: "/mypage/profile/upload_profile_image",
            method: "post",
            headers: {"X-CSRF-TOKEN": token},
            data: fd,
            processData: false,
            contentType: false
        })
        .done(function (result) {
            // アップロード結果を反映する
console.log(result);
            reflectProfileImage(self, $.parseJSON(result));
        });
    });
};

function reflectProfileImage(target, result) {
    // 拡張子，mime-typeエラー
    if (result === "ext_and_mimetype") {
        $("#uploaded_image")
            .append("<span id='upload_image_preview'>画像のアップロードに失敗しました。</span>");
    // ファイルサイズオーバ
    } else if (result === "file_size") {
        $("#uploaded_image")
            .append("<span id='upload_image_preview'>20MB以下の画像を指定して下さい。</span>");
    } else {
        $(target).siblings("img").attr("src", "/uploads/profile/" + result);
    }
};
