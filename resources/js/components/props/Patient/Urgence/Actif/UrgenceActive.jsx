import React from 'react';
import PatientListPU from "../PatientListPU";
import PersonnelCard from "../../../Main/PersonnelCard";
import dateFormat from "dateformat";
import axios from "axios";

class UrgenceActive extends React.Component {
    constructor(props) {
        super(props);
        this.AddPatient = this.AddPatient.bind(this);
        this.getinfos = this.getinfos.bind(this);
        this.state = {
            puid: null,
            personnels: null,
            patient: null,
            plan: [],
            infos: false,
            blessure: 0,
            pname: "",
            prenom: "",
            vetementid: 0,
            vetements: null,
            payed: false,
        };
    }




    async AddPatient(e) {
        e.preventDefault();
        if (this.state.pname !== '' && this.state.prenom !== '' && this.state.blessure !== 0) {
            var req = await axios({
                url: '/data/pu/addpatient/'+this.state.plan.id,
                method: 'POST',
                data: {
                    nom: this.state.pname,
                    prenom: this.state.prenom,
                    blessure: this.state.blessure,
                    vetement: this.state.vetementid,
                    payed: this.state.payed,
                }
            })
            if(req.status === 201){
                await this.getinfos();
                this.setState({
                    blessure: 0,
                    pname: "",
                    prenom: "",
                    vetementid: 0,
                    payed: false,
                })
            }else{
                await this.props.upload();
            }
        }
    }

    async getinfos() {
        var req = await axios({
            url: '/data/pu/getinfos/true',
            method: 'GET'
        })
        if(req.data.status==='pas ok'){
            await this.props.upload();
            this.setState({infos: false})
        }
        this.setState({
            patients: req.data.patients,
            personnels: req.data.personnels,
            plan: req.data.plan,
            name: req.data.name,
            startname: req.data.startname,
            infos: true,
            blessures: req.data.blessures,
            vetements: req.data.vetements,
        })
    }
     componentDidMount() {
         this.getinfos()
         this.timerID = setInterval(
             () => this.getinfos(),
             20*1000
         );

    }
    componentWillUnmount() {
        clearInterval(this.timerID);
    }

    render() {
        return (
            <div className={"UrgenceEnabled"}>
                {this.state.infos &&
                <div className={"urgence"}>
                    <section className={"Header"}>
                        <div className={"title-contain"}>
                            <h1 className={"Title"}>Black code : {this.state.name}</h1>
                        </div>
                        <div className={"alert-creator"}>
                            <h2>{this.state.startname}</h2>
                        </div>
                        <div className={"disable-btn-contain"}>
                            <button className={'btn'} onClick={this.props.ChangeState}>en cours</button>
                        </div>
                    </section>
                    <div className={"AddPatient"}>
                        <h1 className={"Title"}>Ajouter un patient</h1>
                        {
                            this.state.blessures &&
                            <form method={"post"} onSubmit={this.AddPatient}>
                                <input autoComplete={'off'} type={"text"} value={this.state.pname} onChange={(e) => {
                                    this.setState({pname: e.target.value})
                                }} placeholder={"nom"} name={"patient_nom"} className={'Namefield'}/>
                                <input autoComplete={'off'} type={"text"} value={this.state.prenom} onChange={(e) => {
                                    this.setState({prenom: e.target.value})
                                }} placeholder={"prenom"} name={"patient_prenom"} className={'Voramefield'}/>
                                <label className={'Labelfield'}>Type : </label>
                                <select value={this.state.blessure} name={"typeBlessure"} className={'Selectfield'} onChange={(e) => {
                                    this.setState({blessure: e.target.value})
                                }}>
                                    <option value={0} disabled>Choisir une blessure</option>
                                    {this.state.blessures.map((blessure) =>
                                        <option key={blessure.id} value={blessure.id}>{blessure.name}</option>
                                    )}
                                </select>
                                <label className={'LabelfieldV'}>Vetement : </label>
                                <select value={this.state.vetementid} className={'SelectfieldV'} onChange={(e) => {
                                    this.setState({vetementid: e.target.value})
                                }}>
                                    <option value={0} disabled>Choisir un vetement</option>
                                    {this.state.vetements.map((vetement) =>
                                    <option key={vetement.id} value={vetement.id}>{vetement.name}</option>
                                )}
                                </select>
                                <div className={'switch-container'}>
                                    <input id={"switch1"} checked={this.state.payed} className="payed_switch" type="checkbox" onChange={(e) => {
                                        if(this.state.payed){
                                            this.setState({payed:false})
                                        }else{
                                            this.setState({payed:true})
                                        }
                                    }}/>
                                    <label htmlFor={"switch1"} className={"payed_switchLabel"}/>
                                </div>
                                <button type={"submit"} className={'Buttonfield btn'}>Ajouter</button>
                            </form>
                        }
                    </div>
                    <div className={"Participants"}>
                        <h1 className={"Title"}>Personnel engagé</h1>
                        <div className={"Personnel-list"}>
                            {this.state.personnels &&
                                this.state.personnels.map((personnel) =>
                                <PersonnelCard key={personnel.id} name={personnel.name}/>
                            )}
                        </div>
                    </div>
                </div>
                }
                {this.state.infos &&
                <div className={"plan"}>
                    <h1 className={"Title"}>Liste des patient</h1>
                    <div className={"Patient-List"}>
                        {this.state.patients && this.state.patients.map((patient) =>
                            <PatientListPU key={patient.id} name={patient.patient_name}
                                           date={dateFormat(patient.created_at, 'H:MM')} Url={this.props.rootUrl}
                                           urlid={patient.rapport_id}
                                            update={this.getinfos}/>
                        )}

                    </div>
                    {this.state.plan && <h4>Déclanché le {dateFormat(this.state.plan.started_at, 'dd/mm/yyyy à H:MM')}</h4>}
                    {this.state.plan && <h4>Lieux : {this.state.plan.place}</h4>}

                </div>
                }


            </div>
        )
    };

}

export default UrgenceActive;
