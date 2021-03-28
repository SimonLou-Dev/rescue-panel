import React from 'react';
import PatientInfos from "../props/Patient/Recherche/PatientInfos";
import InterventionItem from "../props/Patient/Recherche/InterventionItem";
import axios from "axios";
import dateFormat from 'dateformat';
import * as queryString from "querystring";
import PagesTitle from "../props/utils/PagesTitle";
export const rootUrl = document.querySelector('body').getAttribute('data-root-url');
class RecherchePatient extends React.Component {
    constructor(props) {
        super(props);
        this.state = {inshearch: false, recherche: "",interid: 0, name: null, inter: null, error: false,patient: null, tel: "", pprenom: '', pname: ''};
        this.ShearshSubmit = this.ShearshSubmit.bind(this);
        this.ShearshChange = this.ShearshChange.bind(this);
        this.InterventionCallback = this.InterventionCallback.bind(this);
        this.updatetel = this.updatetel.bind(this);
    }

    async ShearshChange(event) {
        this.setState({recherche: event.target.value});
        if(event.target.value !== ""){
            var req = await axios({
                url: '/data/patient/search/' + event.target.value,
                method: 'GET',
            });
            this.setState({name: req.data.list})
        }
    }

    async updatetel(e) {
        e.preventDefault();
        await axios({
            url: '/data/rapport/changetel/' + this.state.patientid,
            method: 'post',
            data: {
                tel: this.state.tel,
                nom: this.state.pname,
                prenom: this.state.pprenom
            }
        })
    }

    async ShearshSubmit(event) {
        this.setState({recherche: event.target.value});
        event.preventDefault();
        if(this.state.recherche !== ""){
            var req = await axios({
                url: '/data/patient/interlist/' + this.state.recherche,
                method: 'GET',
            });
            if(req.data.status !== "OK"){
                this.setState({error:true});
            }else {
                this.setState({error:false});
            }
            this.setState({
                inter: req.data.inter,
                tel:req.data.patient.tel,
                patient: req.data.patient,
                patientid: req.data.patient.id,
                pname: req.data.patient.name,
                pprenom: req.data.patient.vorname,
            })

        }

    }
    InterventionCallback(id){
        this.setState({interid: id});
    }

    async componentDidMount() {
        let url = this.props.location.search;
        let params = queryString.parse(url);
        var id = 0;
        for (var key in params) {
            if (params.hasOwnProperty(key)) {
                id = params[key];
                var req = await axios({
                    url: '/data/rapport/get/' + id,
                    method: 'GET'
                })
                this.setState({
                    recherche: req.data.patient.vorname + ' ' +req.data.patient.name,
                    patient: req.data.patient,
                    tel: req.data.patient.tel,
                    pname:req.data.patient.name,
                    pprenom: req.data.patient.vorname,
                    interid: id,
                    inter: req.data.rapportlist,
                    patientid: req.data.patient.id,
                })
            }
        }
    }


    render() {
        return (
            <div className={'RecherchePatient'}>
                <section className={'header'}>
                    <PagesTitle title={'Dossiers patients'}/>
                </section>
                <section className={'PatientRechercheContent'}>
                    <section className={'Recherche'}>
                        <div className={'FormRecherche'}>
                            <form method={"post"} onSubmit={this.ShearshSubmit}>
                                <input autoComplete="off" list="autocomplete" type="text" value={this.state.recherche}   placeholder={"rechercher"} name={"recherhce"} onChange={this.ShearshChange}/>
                                <datalist id="autocomplete">
                                    {this.state.name &&
                                        this.state.name.map((option) =>
                                            <option key={option.id} value={option.vorname + ' ' + option.name}/>
                                        )
                                    }
                                </datalist>
                                <button type={"submit"} className={"btn"}><img alt={''} src={rootUrl + 'assets/images/shearch.png'}/></button>
                            </form>
                        </div>
                        {this.state.error &&
                        <div className={'form-error'}>
                            <p style={{textAlign:"center"}}>impossible de trouver le patient</p>
                        </div>
                        }
                        <div className={'Interventions'}>
                            <h3>Liste des interventions</h3>
                            <div className={'InterventionsList'}>
                                {this.state.inter &&
                                    this.state.inter.map((inter) =>
                                        <InterventionItem key={inter.id} inter={"Intervention du " + dateFormat(inter.created_at, 'dd/mm/yyyy à H:MM:ss')} id={inter.id} CallBack={this.InterventionCallback}/>
                                    )
                                }
                            </div>
                        </div>
                        {this.state.inter &&
                            <div className={'infos'}>
                                <form onSubmit={this.updatetel}>

                                    <div className={'inline'}>
                                        <label>n° de tel</label>
                                        <input type={'text'} placeholder={'n° de tel patient'} value={this.state.tel} onChange={(e)=>{this.setState({tel:e.target.value})}}/>
                                    </div>
                                    <div className={'inline'}>
                                        <label>Prénom</label>
                                        <input type={'text'} placeholder={'prénom'} value={this.state.pprenom} onChange={(e)=>{this.setState({pprenom:e.target.value})}}/>
                                    </div>

                                    <div className={'inline'}>
                                        <label>Nom</label>
                                        <input type={'text'} placeholder={'nom'} value={this.state.pname} onChange={(e)=>{this.setState({pname:e.target.value})}}/>
                                    </div>
                                    <button type={'text'} className={'btn'}>Valider</button>
                                </form>
                            </div>
                        }

                    </section>
                    {this.state.interid !== 0
                        ? <PatientInfos CanModify={true} id={this.state.interid}/>
                        :<div className={'PatientInfos'}/>
                    }
                </section>

            </div>
        )
    }
}

export default RecherchePatient;
