$(function () {
    if ($("#notification_header").length > 0) {
        // 通知を一定時間表示後，非表示に
        $("#notification_header").animate_notification();
    }
});

$.fn.animate_notification = function () {
    // 通知の表示
    $("#notification_header").css({"top":"0px"}).fadeIn(1000).delay(2000).fadeOut(2000);
};

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

"use strict";

$(function() {
    if ($("p[name^=word_count_]").length > 0) {
        $("p[name^=word_count_]").count_word();
    }
});

// フォームの入力文字数をカウント
$.fn.count_word = function () {
    $(this).each(function () {
        var self = $(this);
        var target_id = "#" + self.data("target");
        var word_length = $(target_id).val().length || 0;

        // 初回表示時
        display_word_counter(self, word_length);

        // 入力時
        $(target_id).bind("keydown keyup change", function () {
            word_length = $(target_id).val().length;
            display_word_counter(self, word_length);
        });
    });
};

// 文字数の表示
var display_word_counter = function (self, word_length) {
    // 文字数上限
    var count_limit = self.data("limit");

    // 文字数オーバー
    if (count_limit < word_length) {
        self.empty().append("<span style='color:#FF0000;'>" + word_length + "</span>/<span>" + count_limit + "</span>文字</span>");
    } else {
        self.empty().append("<span>" + word_length + "</span>/<span>" + count_limit + "文字</span>");
    }
};
