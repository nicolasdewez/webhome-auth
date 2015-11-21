function List(selectorActive, selectorActions)
{
    this.selectorActive = selectorActive;
    this.selectorActions = selectorActions;
    this.ajax = new Ajax();

    this.changeState = function(e, activate) {
        e.preventDefault();
        var link = $(e.currentTarget);
        var line = link.parent().parent();
        var active = line.find(this.selectorActive + ' .glyphicon-ok');
        var inactive = line.find(this.selectorActive + ' .glyphicon-remove');
        var activateLink = line.find(this.selectorActions + ' .glyphicon-ok');
        var deactivateLink = line.find(this.selectorActions + ' .glyphicon-remove');

        this.ajax.patch(link.attr('rel'), null, function() {
            if (!activate) {
                inactive.removeClass('hide');
                deactivateLink.addClass('hide');
                active.addClass('hide');
                activateLink.removeClass('hide');
                return;
            }
            active.removeClass('hide');
            activateLink.addClass('hide');
            inactive.addClass('hide');
            deactivateLink.removeClass('hide');
        }.bind(this));
    };

    this.deleteElement = function(e) {
        e.preventDefault();
        var link = $(e.currentTarget);
        var line = link.parent().parent();
        this.ajax.remove(link.attr('rel'), null, function() {
            line.remove();
        }.bind(this));
    }
}
