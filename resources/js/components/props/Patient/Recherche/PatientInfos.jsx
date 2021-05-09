import React from 'react';
import axios from "axios";
import dateFormat from 'dateformat';

class PatientInfos extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            rapport: null,
            id: null,
            name: null,
            prenom: null,
            tel: null,
            type: null,
            transport: null,
            desc: null,
            montant: null,
            payed: null,
            startdate: null,
            starttime: null,
            enddate: null,
            endtime: null,
            error: false,
            succsess: false,
        }
        this.post= this.post.bind(this);
    }

    async componentDidMount() {
        this.setState({id:this.props.id})
        var req = await axios({
            url: '/data/rapport/get/'+this.props.id,
        })
        this.setState({
            types: req.data.types,
            broum: req.data.broum,
            rapport: req.data.rapport,
            type: req.data.rapport.get_type.id,
            transport: req.data.rapport.get_transport.id,
            desc: req.data.rapport.description,
            montant: req.data.rapport.price
        });


        if(req.data.rapport.ATA_start != null){
            this.setState({
                startdate: dateFormat(req.data.rapport.ATA_start, 'yyyy-mm-dd'),
                starttime: dateFormat(req.data.rapport.ATA_start, 'H:MM'),
                enddate: dateFormat(req.data.rapport.ATA_end, 'yyyy-mm-dd'),
                endtime: dateFormat(req.data.rapport.ATA_end, 'H:MM'),
            })
        }else{
            this.setState({
                startdate: '0000-00-00',
                starttime: '00:00',
                enddate: '0000-00-00',
                endtime: '00:00',
            })
        }
    }

    componentDidUpdate(prevProps, prevState, snapshot) {
        if(this.props.id !== this.state.id){
            this.componentDidMount();
        }
    }

    async post(e) {
        e.preventDefault();
        var req = await axios({
            url: '/data/rapport/update/'+this.state.id,
            method: 'PUT',
            data: {
                type: this.state.type,
                transport: this.state.transport,
                desc: this.state.desc,
                montant: this.state.montant,
                startdate: this.state.startdate,
                starttime: this.state.starttime,
                enddate: this.state.enddate,
                endtime: this.state.endtime,
            }
        })
        if(req.status === 201){
            this.setState({succsess: true,req: req});
        }else{
            this.setState({error: true});
        }
    }

    render() {
        return (
            <div className={'PatientInfos'}>
                {this.state.rapport &&
                    <section className={"form"}>
                        <form method={'POST'} onSubmit={this.post}>
                            <label className={"DescLabel"} >Description :</label>
                            <textarea autoComplete={'off'} className={"DescInput"} value={this.state.desc} onChange={(e)=>{this.setState({desc:e.target.value})}}/>
                            <div className={"InterTypeLabel"}>
                                <label >Type d'intervention :</label>
                            </div>
                            <select value={this.state.type} className={"InterInput"} onChange={(e)=>{this.setState({type:e.target.value})}}>
                                {this.state.types.map((type)=>
                                    <option key={type.id} value={type.id}>{type.name}</option>
                                )}
                            </select>
                            <div className={"BroumLabel"}>
                                <label>Transport :</label>
                            </div>
                            <select value={this.state.transport} className={"BroumInput"} onChange={(e)=>{this.setState({transport:e.target.value})}}>
                                {this.state.broum.map((type)=>
                                    <option key={type.id} value={type.id}>{type.name}</option>
                                )}
                            </select>
                            <div className={"TarifLabel"}>
                                <label >Montant de la facture :</label>
                            </div>
                            <input autoComplete={'off'} className={"TarifInput"} value={this.state.montant} onChange={(e)=>{this.setState({montant:e.target.value})}}/>
                            <div className={"ATA"}>
                                <label className={"ATA_Label"} >ATA du</label>
                                <input type={'date'} className={"date-aInput"} value={this.state.startdate} onChange={(e)=>{this.setState({startdate:e.target.value})}}/>
                                <label className={"ATA-b_Label"} >à</label>
                                <input type="time" className={"time-aInput"} value={this.state.starttime} onChange={(e)=>{this.setState({starttime:e.target.value})}}/>
                                <label className={"ATA-c_Label"} >Au</label>
                                <input type={'date'} className={"date-bInput"} value={this.state.enddate} onChange={(e)=>{this.setState({enddate:e.target.value})}}/>
                                <label className={"ATA-d_Label"} >à</label>
                                <input type="time" className={"time-bInput"} value={this.state.endtime} onChange={(e)=>{this.setState({endtime:e.target.value})}}/>
                            </div>
                            <button type={"submit"} className={"btn submit"}>Sauvegarder</button>
                            <a target={'_blank'} href={'/pdf/rapport/'+this.state.rapport.id} className={"PDF btn"}>Générer PDF</a>
                        </form>
                    </section>
                }
            </div>
        )
    }
}

export default PatientInfos;
