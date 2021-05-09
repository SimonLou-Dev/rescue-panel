import React from 'react'
import axios from "axios";

class Informations extends React.Component{
    constructor(props) {
        super(props);
        this.nomchange = this.nomchange.bind(this);
        this.telchange = this.telchange.bind(this);
        this.prenomchange = this.prenomchange.bind(this);
        this.starttimechange = this.starttimechange.bind(this);
        this.startdatechange = this.startdatechange.bind(this);
        this.state = {
            list: null,
        }
    }

    telchange(e){
        this.props.onTelChange(e.target.value);
    }

    async nomchange(e) {
        this.props.onNameChange(e.target.value);
        var req = await axios({
            url: '/data/patient/search/' + e.target.value,
            method: 'GET',
        });
        this.setState({list: req.data.list})

    }

    prenomchange(e){
        this.props.onPrenomChange(e.target.value);
    }

    starttimechange(e){
        const startinter = this.props.startinter.split(' ');
        this.props.onStartChange(startinter[0] + ' ' + e.target.value)
    }
    startdatechange(e){
        const startinter = this.props.startinter.split(' ');
        this.props.onStartChange(e.target.value + ' ' + startinter[1])
    }

    render() {
        const name = this.props.name;
        const prenom = this.props.prenom;
        const tel = this.props.tel;
        const b = ' '
        const startinter = this.props.startinter ? this.props.startinter.split(' ') : b.split(' ');
        return(
            <div className={'Rapport-Card'}>
                <h1>Informations</h1>
                <div className="Form-Group">
                    <input required type="text" className={(this.props.errors.name ? 'form-error': '')} list={'autocomplete'} autoComplete={'off'} placeholder="prénom nom" value={name} onChange={this.nomchange}/>
                    {this.state.list &&
                        <datalist id={'autocomplete'}>
                            {this.state.list.map((item)=>
                                <option>{item.vorname} {item.name}</option>
                            )}
                        </datalist>
                    }
                    {this.props.errors.name &&
                        <ul className={'error-list'}>
                            {this.props.errors.name.map((item)=>
                                <li>{item}</li>
                            )}
                        </ul>
                    }
                    <input type="text" autoComplete={'off'} placeholder="n° de tel" value={tel} onChange={this.telchange}/>
                    <label>Début d'intervention</label>
                    <input required type={'date'} autoComplete={'off'} value={startinter[0]} onChange={this.startdatechange}/>
                    <input required type={'time'} autoComplete={'off'} value={startinter[1]} onChange={this.starttimechange}/>
                </div>
            </div>
        )
    }
}
export default Informations
