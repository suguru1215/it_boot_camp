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
