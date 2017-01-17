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
