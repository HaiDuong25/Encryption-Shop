'use strict';
var notify = $.notify('<i class="fas fa-bell"></i></i><strong>Đang tải dữ liệu</strong>....', {
    type: 'theme',
    allow_dismiss: true,
    delay: 1800,
    showProgressbar: true,
    timer: 200,
    // timer: 555555500,
    animate: {
        enter: 'animated fadeInDown',
        exit: 'animated fadeOutUp'
    }
});

setTimeout(function () {
    notify.update('message', '<i class="fas fa-bell"></i></i><strong>Dữ liệu</strong> đã được cập nhật thành công.');
}, 1000);