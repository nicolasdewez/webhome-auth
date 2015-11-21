function Notifier()
{
    this.notifications = $('.flash');
    this.prototype = this.notifications.attr('data-prototype');

    this.addNotification = function (type, text) {
        var html = this.prototype.replace('$$TYPE$$', type).replace('$$MESSAGE$$', text);
        this.notifications.append(html);
    };

    this.error = function (text) {
        this.addNotification('danger', text);
    };

    this.notice = function(text) {
        this.addNotification('info', text);
    };
}
