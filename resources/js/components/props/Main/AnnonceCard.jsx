import React from 'react';
var date;
class AnnonceCard extends React.Component{
    constructor(props) {
        super(props);
         date = this.props.date.split("-");
    }

    render() {return(
        <div className={'Annonce-Card'}>
            <h3 className={'Title'}dangerouslySetInnerHTML={{__html:this.props.title}}/>
            <div className={'Separator'}/>
            <p className={'Text'} dangerouslySetInnerHTML={{__html:this.props.content}}/>
            <div className={'Separator'}/>
            <h4 className={'Date'}>{this.props.date}</h4>
        </div>
    );}
}
export default AnnonceCard;
