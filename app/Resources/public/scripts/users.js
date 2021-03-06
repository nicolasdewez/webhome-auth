function UsersList (table)
{
    this.table = table;
    this.deactiveLinks = this.table.find('.appUsersList-actions .glyphicon-remove');
    this.activeLinks = this.table.find('.appUsersList-actions .glyphicon-ok');
    this.deleteLinks = this.table.find('.appUsersList-actions .glyphicon-trash');

    this.list = new List('.appUsersList-active', '.appUsersList-actions');

    this.onClickDeactivateLink = function(e){
        this.list.changeState(e, false);
    };

    this.onClickActivateLink = function(e){
        this.list.changeState(e, true);
    };

    this.onClickDeleteLink = function(e){
        this.list.deleteElement(e);
    };

    this.listenEvents = function() {
        this.deactiveLinks.click(this.onClickDeactivateLink.bind(this));
        this.activeLinks.click(this.onClickActivateLink.bind(this));
        this.deleteLinks.click(this.onClickDeleteLink.bind(this));
    };

    this.listenEvents();
}
