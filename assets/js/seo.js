window.send_counter_event = function (target) {

    if (typeof Ya != "undefined" && typeof Object.keys != "undefined") {
        var counter = Ya._metrika.counters[Object.keys(Ya._metrika.counters)[0]];
        counter.reachGoal(target, function () {
            console.log('sended ' + target);
        });
    } else {
        console.log('did not found Ya._metrika.counters');
    }
};

$(document).on('click', '.js-reachgoal', function () {
    window.send_counter_event($(this).data('reachgoal'));
});
// push_callback_button - нажатие на кнопку обратного звонка
// submit_ + name формы - попытка отправки формы
// success_send_ + name формы - успешная отправка формы