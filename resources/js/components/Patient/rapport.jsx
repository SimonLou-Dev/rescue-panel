import React from "react";
import Intervention from '../props/Patient/Rapport/Intervention';
import Facturation from "../props/Patient/Rapport/Facturation";
import Informations from "../props/Patient/Rapport/Informations";
import ATA from "../props/Patient/Rapport/ATA";
import axios from "axios";
import PagesTitle from "../props/utils/PagesTitle";

class Rapport extends React.Component{

    constructor(props) {
        super(props);
        this.handleSubmit = this.handleSubmit.bind(this);
        this.state = {
            name: "",
            prenom: "",
            tel: "",
            type: 0,
            transport: 1,
            desc: '',
            montant: null,
            payed: true,
            startdate: '',
            starttime: '',
            enddate: '',
            endtime: '',
            pdf: null,
            error: false,
            succsess: false,
            req: null,
        }
    }

    async handleSubmit(event) {
        console.log(this.state.startdate)
        event.preventDefault();
        var req = await axios({
            url: '/data/rapport/post',
            method: 'POST',
            data: {
                name: this.state.name,
                prenom: this.state.prenom,
                tel: this.state.tel,
                type: this.state.type,
                transport: this.state.transport,
                desc: this.state.desc,
                montant: this.state.montant,
                payed: this.state.payed,
                startdate: this.state.startdate,
                starttime: this.state.starttime,
                enddate: this.state.enddate,
                endtime: this.state.endtime,
            }
        })

        if(req.status === 201){
            this.setState({succsess: true,req: req,
                name: "",
                prenom: "",
                tel: "",
                type: 0,
                transport: 1,
                desc: '',
                montant: null,
                payed: true,
                startdate: '',
                starttime: '',
                enddate: '',
                endtime: '',});

        }else{
            this.setState({error: true});
        }

    }

    //
    //

    render() {
        return(
            <div id={'Rapport-Patient'}>
                {this.state.succsess &&
                    <div className={'card-ok'}>
                        <h1>Le rapport n°{this.state.req.data.rapport.id} a  été ajoué et assiginé au patient {this.state.req.data.patient.name} {this.state.req.data.patient.vorname}</h1>
                        <button className={'btn'} onClick={()=>this.setState({succsess:false})}>OK</button>
                    </div>
                }
                {this.state.error &&
                    <div className={'card-error'}>
                        <h1>Erreur lors de la création du rapport</h1>
                        <button className={'btn'} onClick={()=>this.setState({error:false})}>OK</button>
                    </div>
                }




                <form method={'POST'} onSubmit={this.handleSubmit}>
                <div className={'Header'}>
                    <div className={"submit"}>
                        <button type={"submit"}>Enregistrer</button>
                    </div>
                    <PagesTitle title={'Rapport patient'}/>
                </div>
                <div className={'content'}>

                        <Informations name={this.state.name} prenom={this.state.prenom} tel={this.state.tel} onNameChange={(str) =>{this.setState({name:str});}} onPrenomChange={(str) => {this.setState({prenom:str});}} onTelChange={(str) =>  {this.setState({tel:str});}}/>
                        <Intervention type={this.state.type} transport={this.state.transport} description={this.state.desc} onTypeChange={(str) =>{this.setState({type:str});}} onTransportChange={(str) => {this.setState({transport:str});}} onDescChange={(str) => {this.setState({desc:str});}}/>
                        <Facturation payed={this.state.payed} montant ={this.state.montant} onPayedChange={(str) => {this.setState({payed:str});}} onMotantChange={(str) => {this.setState({montant:str});}}/>
                        <ATA startDate={this.state.startdate} startTime={this.state.starttime} endDate={ this.state.enddate} endTime={ this.state.endtime} onStartDateChange={(str) => {this.setState({startdate:str});}} onStartTimeChange={(str) => {this.setState({starttime:str});}} onEndDateChange={(str) => {this.setState({enddate:str});}} onEndTimeChange={(str) => {this.setState({endtime:str});}}/>
                </div>
                </form>
            </div>
        );
    }
}
export default Rapport
