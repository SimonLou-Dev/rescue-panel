import React from 'react';
import PatientListPU from "../PatientListPU";
import dateFormat from "dateformat";


class UrgenceDisabled extends React.Component {
    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleSelectChange = this.handleSelectChange.bind(this);
        this.state = {
            place: "",
            type: 1,
            publied: false,
            patients: null,
            personnels: null,
            lastplan: null,
            types:null};
    }
    handleChange(event) {
        this.setState({place: event.target.value});
    }
    handleSelectChange(event) {
        this.setState({type: event.target.value});
    }
    handleSubmit(event){
        if(this.state.place !== ""){
            this.props.ChangeState(this.state.type, this.state.place);
        }


        event.preventDefault();
    }

    async componentDidMount() {
        var req = await axios({
            url: '/data/pu/getinfos/false',
            method: 'GET'
        })
        this.setState({
            patients: req.data.lastpatients,
            personnels: req.data.lastpersonnels,
            lastplan: req.data.lastplan,
            types: req.data.types
        })
        console.log(req.data.lastpersonnels);


    }

    render() {
        return (
            <div className={"urgence_disabled"}>
                <div className={"starter"}>
                    <h1 className={"Title"}>Déclenchement black code</h1>
                    <form method={"post"} onSubmit={this.handleSubmit}>
                        <div className={'Line-Form-group'}>
                            <label>Lieux</label>
                            <input autoComplete={'off'} name="place" value={this.state.place} onChange={this.handleChange} type={"text"} placeholder={"Lieux"}/>
                        </div>
                        <div className={'Line-Form-group'}>
                            <label>Type</label>
                            <select value={this.state.type} onChange={this.handleSelectChange}>
                                {this.state.types &&
                                    this.state.types.map((type) =>
                                        <option key={type.id} value={type.id}>{type.name}</option>
                                    )
                                }
                            </select>
                        </div>
                        <button type={"submit"} className={'btn'}>activer</button>
                    </form>
                </div>
                <div className={"plan"}>
                    <h1 className={"Title"}>Dernier black code</h1>
                    <div className={"Patient-List"}>
                        {this.state.patients &&
                            this.state.patients.map((patient)=>
                                <PatientListPU name={patient.patient_name}
                                               date={dateFormat(patient.created_at, 'H:MM')} Url={this.props.rootUrl}
                                               urlid={patient.rapport_id}
                                               update={this.componentDidMount}/>

                            )
                        }

                    </div>
                    <div className={'PersonnelList'}>
                        <ul>
                            {this.state.personnels &&
                                this.state.personnels.map((perso)=>
                                    <li key={perso.id}>{perso.name}</li>
                                )

                            }


                        </ul>
                    </div>
                    {this.state.lastplan&&
                        <h4>{dateFormat(this.state.lastplan.created_at, 'dd/mm/yyyy à H:MM')}</h4>}
                    {this.state.lastplan &&
                        <h4>Lieux : {this.state.lastplan.place}</h4>
                    }


                </div>
            </div>
        )
    };


}

export default UrgenceDisabled;
