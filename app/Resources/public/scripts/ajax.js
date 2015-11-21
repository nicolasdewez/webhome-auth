function Ajax()
{
    this.notifier = new Notifier;

    this.call = function (url, parameters, method, success, fail) {
        var $this = this;

        $.ajax({
            url: url,
            data: parameters,
            method: method
        }).done(function(data, status, object) {
            $this.notifier.notice(data.message);
            if (typeof success === 'function') {
                success();
            }
        }).fail(function(object, status, error) {
            $this.notifier.error(status);
            if (typeof fail === 'function') {
                fail();
            }
        });
    };

    this.patch = function (url, parameters, success, fail) {
        this.call(url, parameters, 'PATCH', success, fail);
    };

    this.remove = function (url, parameters, success, fail) {
        this.call(url, parameters, 'DELETE', success, fail);
    };
}
