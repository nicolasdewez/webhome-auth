function AuthorizationsList (list)
{
    this.list = list;
    this.checkbox = this.list.find('input[type=checkbox]');

    this.onClickCheckbox = function(e){
        var checkbox = $(e.currentTarget);
        if (!checkbox.is(':checked')) {
            return this.uncheck(checkbox);
        }
        this.check(checkbox);
    };

    this.check = function(checkbox) {
        var currentCode = checkbox.parent().find('label').text();
        var codeAuthorization = currentCode.substr(0, currentCode.lastIndexOf('_'));
        this.list.find('label').each(function(key, item) {
            var code = $(item).text();
            if (code !== codeAuthorization) {
                return;
            }
            checkbox = $(item).parent().find('input[type=checkbox]');
            if (checkbox.is(':checked')) {
                return;
            }
            checkbox.click();
        });
    };

    this.uncheck = function(checkbox) {
        var currentCode = checkbox.parent().find('label').text();
        var lengthCurrentCode = currentCode.length;
        var countUnderscoreCurrent = currentCode.split('_').length - 1;

        this.list.find('label').each(function(key, item) {
            var code = $(item).text();
            var countUnderscore = code.split('_').length - 1;
            if (countUnderscore !== countUnderscoreCurrent + 1 || code.substr(0, lengthCurrentCode) !== currentCode) {
                return;
            }
            checkbox = $(item).parent().find('input[type=checkbox]');
            if (!checkbox.is(':checked')) {
                return;
            }
            checkbox.click();
        });
    };

    this.listenEvents = function() {
        this.checkbox.click(this.onClickCheckbox.bind(this));
    };

    this.listenEvents();
}
