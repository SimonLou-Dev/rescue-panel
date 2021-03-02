import React from 'react'

class Informations extends React.Component{
    constructor(props) {
        super(props);
        this.nomchange = this.nomchange.bind(this);
        this.telchange = this.telchange.bind(this);
        this.prenomchange = this.prenomchange.bind(this);
    }

    telchange(e){
        this.props.onTelChange(e.target.value);
    }
    nomchange(e){
        console.log('call');
        this.props.onNameChange(e.target.value);
    }
    prenomchange(e){
        this.props.onPrenomChange(e.target.value);
    }

    render() {
        const name = this.props.name;
        const prenom = this.props.prenom;
        const tel = this.props.tel;
        return(
            <div className={'Rapport-Card'}>
                <h1>Informations</h1>
                <div className="Form-Group">
                    <input required type="text" autoComplete={'off'} placeholder="nom" value={name} onChange={this.nomchange}/>
                    <input required type="text" autoComplete={'off'} placeholder="prénom" value={prenom} onChange={this.prenomchange}/>
                    <input type="text" autoComplete={'off'} placeholder="n° de tel" value={tel} onChange={this.telchange}/>
                </div>
            </div>
        )
    };
}
export default Informations
