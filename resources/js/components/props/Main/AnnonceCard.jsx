import React from 'react';
var date;
class AnnonceCard extends React.Component{
    constructor(props) {
        super(props);
         date = this.props.date.split("-");
         date = date[2] + '/' + date[1] + '/' + date[0];
    }

    render() {return(
        <div className={'Annonce-Card'}>
            <h3 className={'Title'}>{this.props.title}</h3>
            <div className={'Separator'}/>
            <p className={'Text'}>{this.props.content}</p>
            <div className={'Separator'}/>
            <h4 className={'Date'}>{date}</h4>
        </div>
    );}
}
export default AnnonceCard;
