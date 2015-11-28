function ForgottenPasswordsList (table)
{
    this.table = table;
    this.deleteLinks = this.table.find('.appForgottenPasswordsList-actions .glyphicon-trash');

    this.list = new List();

    this.onClickDeleteLink = function(e){
        this.list.deleteElement(e);
    };

    this.listenEvents = function() {
        this.deleteLinks.click(this.onClickDeleteLink.bind(this));
    };

    this.listenEvents();
}
